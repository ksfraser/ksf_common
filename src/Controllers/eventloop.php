<?php

namespace Ksfraser\Common\Controllers;

use Ksfraser\Common\Core\kfLog;
use SplSubject;
use SplObserver;
use SplObjectStorage;

/**
 * Class eventloop
 * Implements a workflow engine for managing events and observers.
 *
 * @package Ksfraser\Common\Controllers
 */
class eventloop extends kfLog implements SplSubject
{
    /**
     * @var array Configuration values for the eventloop.
     */
    var $config_values = array();

    /**
     * @var array Tabs for the eventloop interface.
     */
    var $tabs = array();

    /**
     * @var string|null Help context for the eventloop.
     */
    var $help_context;

    /**
     * @var string|null Table prefix for database operations.
     */
    var $tb_pref;

    /**
     * @var array List of registered observers.
     */
    private $observers = [];

    /**
     * @var SplObjectStorage Storage for observer objects.
     */
    private $storage;

    /**
     * @var array Queue of events to be processed.
     */
    private $eventQueue = [];

    /**
     * @var mixed|null Caller object for the eventloop.
     */
    protected $caller;

    /**
     * @var string|null Directory for module files.
     */
    protected $moduledir;

    /**
     * Constructor to initialize the eventloop.
     *
     * @param string|null $moduledir Directory for module files.
     * @param mixed|null $caller Caller object for initialization.
     */
    function __construct($moduledir = null, $caller = null)
    {
        parent::__construct();
        $this->caller = $caller;
        $this->storage = new SplObjectStorage();
        $this->initEventGroup('*');
        $this->initEventGroup('**');
        if (!isset($moduledir)) {
            $moduledir = dirname(__FILE__) . '/modules';
        }
        $this->set_moduledir($moduledir);
        $this->load_modules();
        $this->ObserverNotify($this, 'NOTIFY_LOG_INFO', "Completed Adding Modules");
        $this->ObserverNotify($this, 'NOTIFY_INIT_CONTROLLER_COMPLETE', "Completed Adding Modules");
    }

    /**
     * Build the list of events the eventloop is interested in.
     */
    function build_interestedin()
    {
        parent::build_interestedin();
        $this->interestedin['NOTIFY_DUMP_OBSERVERS']['function'] = "dumpObservers";
    }

    /**
     * Set the directory for module files.
     *
     * @param string $dir Directory path.
     */
    function set_moduledir($dir)
    {
        $this->moduledir = $dir;
    }

    /**
     * Dump the list of registered observers.
     *
     * @param mixed $obj Triggering object.
     * @param string $msg Message to log.
     */
    function dumpObservers($obj, $msg)
    {
        if (isset($this->observers)) {
            foreach ($this->observers as $key => $val) {
                echo "Observer Event: " . $key . " with value " . $val;
            }
        }
    }

    /**
     * Register an observer for a specific event.
     *
     * @param SplObserver $observer The observer to register.
     * @param string $event The event to register for.
     * @param int $priority Priority of the observer.
     */
    function ObserverRegister($observer, $event, $priority = 10)
    {
        if (!isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }
        $this->observers[$event][] = ['observer' => $observer, 'priority' => $priority];
        usort($this->observers[$event], function ($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    }

    /**
     * Deregister an observer.
     *
     * @param SplObserver $observer The observer to deregister.
     */
    function ObserverDeRegister($observer)
    {
        $this->observers = array_diff($this->observers, array($observer));
    }

    /**
     * Initialize an event group.
     *
     * @param string $event The event group to initialize.
     */
    private function initEventGroup($event = "*")
    {
        if (!isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }
    }

    /**
     * Notify observers of an event.
     *
     * @param mixed $trigger_class The triggering class.
     * @param string $event The event name.
     * @param mixed $msg The event message.
     */
    function ObserverNotify($trigger_class, $event, $msg)
    {
        $this->Log("Event triggered: $event", PEAR_LOG_INFO);
        if (isset($this->observers[$event])) {
            foreach ($this->observers[$event] as $entry) {
                $result = $entry['observer']->notified($trigger_class, $event, $msg);
                if ($result === false) {
                    break; // Stop propagation
                }
            }
        }
        if (isset($this->observers['**'])) {
            foreach ($this->observers['**'] as $obs) {
                $obs->notified($trigger_class, $event, $msg);
            }
        }
    }

    /**
     * Get the list of observers for an event.
     *
     * @param string $event The event name.
     * @return array The list of observers.
     */
    private function getEventObservers($event = "*")
    {
        $this->initEventGroup($event);
        $group = $this->observers[$event];
        $all = $this->observers["*"];
        return array_merge($group, $all);
    }

    /**
     * Load modules for the eventloop.
     */
    function load_modules()
    {
        global $configArray;
        foreach (glob("{$this->moduledir}/config.*.php") as $filename) {
            include_once($filename);
        }
        $modarray = array();
        $tabarray = array();
        if (isset($configArray) && count($configArray) > 0) {
            foreach ($configArray as $carray) {
                $modarray[$carray['loadpriority']][] = $carray;
                if (isset($carray['taborder'])) {
                    $tabarray[$carray['taborder']][] = $carray;
                } else {
                    $tabarray[99][] = $carray;
                }
            }
        }
        if (isset($modarray) && count($modarray) > 0) {
            foreach ($modarray as $priarray) {
                foreach ($priarray as $marray) {
                    $res = @include_once($this->moduledir . "/" . $marray['loadFile']);
                    if ($res) {
                        $this->ObserverNotify($this, 'NOTIFY_LOG_INFO', "Module " . $marray['ModuleName'] . " being added");
                        $marray['objectName'] = new $marray['className'];
                        if (isset($marray['objectName']->observers)) {
                            foreach ($marray['objectName']->observers as $obs) {
                                $this->observers[] = $obs;
                            }
                        }
                    } else {
                        $this->ObserverNotify($this, 'NOTIFY_LOG_INFO', "Unable to add module" . $this->moduledir);
                    }
                }
            }
        }
        $tabs = array();
        if (isset($tabarray) && count($tabarray) > 0) {
            foreach ($tabarray as $priarray) {
                foreach ($priarray as $tabinc) {
                    $tabs[] = array(
                        'title' => $tabinc['tabdata']['tabtitle'],
                        'action' => $tabinc['tabdata']['action'],
                        'form' => $tabinc['tabdata']['form'],
                        'hidden' => $tabinc['tabdata']['hidden'],
                        'class' => $tabinc['tabdata']['class']
                    );
                }
            }
            $this->tabs = $tabs;
        }
    }

    /**
     * Attach an observer to the eventloop.
     *
     * @param SplObserver $observer The observer to attach.
     * @param string $event The event name.
     */
    public function attach(SplObserver $observer, $event = "*")
    {
        $this->initEventGroup($event);
        $this->observers[$event][] = $observer;
        $this->storage->attach($observer);
    }

    /**
     * Detach an observer from the eventloop.
     *
     * @param SplObserver $observer The observer to detach.
     * @param string $event The event name.
     */
    public function detach(SplObserver $observer, $event = "*")
    {
        $this->storage->detach($observer);
        foreach ($this->getEventObservers($event) as $key => $s) {
            if ($s === $observer) {
                unset($this->observers[$event][$key]);
            }
        }
    }

    /**
     * Notify all observers of an event.
     *
     * @param string $event The event name.
     * @param mixed $data The event data.
     */
    public function notify($event = "*", $data = null)
    {
        foreach ($this->getEventObservers($event) as $observer) {
            $observer->update($this, $event, $data);
        }
        foreach ($this->storage as $obj) {
            $obj->update($this);
        }
    }

    /**
     * Add an event to the queue.
     *
     * @param Event $event The event to queue.
     */
    function queueEvent($event)
    {
        $this->eventQueue[] = $event;
    }

    /**
     * Process all events in the queue.
     */
    function processEventQueue()
    {
        while (!empty($this->eventQueue)) {
            $event = array_shift($this->eventQueue);
            $this->ObserverNotify($this, $event->name, $event);
        }
    }
}

class Event
{
    public $name;
    public $context;
    public $payload;

    public function __construct($name, $context = [], $payload = [])
    {
        $this->name = $name;
        $this->context = $context;
        $this->payload = $payload;
    }
}