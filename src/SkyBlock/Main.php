<?php

/**
 * This is GiantQuartz property.
 *
 * Copyright (C) 2016 GiantQuartz
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author GiantQuartz
 *
 */

namespace SkyBlock;

use pocketmine\plugin\PluginBase;
use SkyBlock\chat\ChatHandler;
use SkyBlock\command\SkyBlockCommand;
use SkyBlock\generator\SkyBlockGeneratorManager;
use SkyBlock\invitation\InvitationHandler;
use SkyBlock\island\IslandManager;
use SkyBlock\reset\ResetHandler;
use SkyBlock\skyblock\SkyBlockManager;

class Main extends PluginBase {

    /** @var Main */
    private static $object = null;

    /** @var SkyBlockGeneratorManager */
    private $skyBlockGeneratorManager;

    /** @var SkyBlockManager */
    private $skyBlockManager;

    /** @var IslandManager */
    private $islandManager;

    /** @var InvitationHandler */
    private $invitationHandler;

    /** @var ResetHandler */
    private $resetHandler;

    /** @var ChatHandler */
    private $chatHandler;

    /** @var EventListener */
    private $eventListener;

    public function onLoad() {
        if(!self::$object instanceof Main) {
            self::$object = $this;
        }
    }

    public function onEnable() {
        $this->initialize();
        $this->setSkyBlockGeneratorManager();
        $this->setSkyBlockManager();
        $this->setIslandManager();
        $this->setEventListener();
        $this->setInvitationHandler();
        $this->setChatHandler();
        $this->setResetHandler();
        $this->setPluginHearbeat();
        $this->registerCommand();
        $this->getLogger()->info("SkyBlock by @GiantQuartz was enabled.");
    }

    public function onDisable() {
        $this->getLogger()->info("SkyBlock by @GiantQuartz was disabled.");
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public static function getInstance() {
        return self::$object;
    }

    /**
     * Return SkyBlockGeneratorManager instance
     *
     * @return SkyBlockGeneratorManager
     */
    public function getSkyBlockGeneratorManager() {
        return $this->skyBlockGeneratorManager;
    }

    /**
     * Return SkyBlockManager instance
     *
     * @return SkyBlockManager
     */
    public function getSkyBlockManager() {
        return $this->skyBlockManager;
    }

    /**
     * Return island manager
     *
     * @return IslandManager
     */
    public function getIslandManager() {
        return $this->islandManager;
    }

    /**
     * Return EventListener instance
     *
     * @return EventListener
     */
    public function getEventListener() {
        return $this->eventListener;
    }

    /**
     * Return InvitationHandler instance
     *
     * @return InvitationHandler
     */
    public function getInvitationHandler() {
        return $this->invitationHandler;
    }

    /**
     * Return ResetHandler instance
     *
     * @return ResetHandler
     */
    public function getResetHandler() {
        return $this->resetHandler;
    }

    /**
     * Return ChatHandler instance
     *
     * @return ChatHandler
     */
    public function getChatHandler() {
        return $this->chatHandler;
    }

    /**
     * Register SkyBlockGeneratorManager instance
     */
    public function setSkyBlockGeneratorManager() {
        $this->skyBlockGeneratorManager = new SkyBlockGeneratorManager($this);
    }

    /**
     * Register SkyBlockManager instance
     */
    public function setSkyBlockManager() {
        $this->skyBlockManager = new SkyBlockManager($this);
    }

    /**
     * Register IslandManager instance
     */
    public function setIslandManager() {
        $this->islandManager = new IslandManager($this);
    }

    /**
     * Register EventListener instance
     */
    public function setEventListener() {
        $this->eventListener = new EventListener($this);
    }

    /**
     * Schedule the PluginHearbeat
     */
    public function setPluginHearbeat() {
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new PluginHearbeat($this), 20);
    }

    /**
     * Register InvitationHandler instance
     */
    public function setInvitationHandler() {
        $this->invitationHandler = new InvitationHandler($this);
    }

    /**
     * Register ResetHandler instance
     */
    public function setResetHandler() {
        $this->resetHandler = new ResetHandler();
    }

    /**
     * Register ChatHandler instance
     */
    public function setChatHandler() {
        $this->chatHandler = new ChatHandler($this);
    }

    /**
     * Register SkyBlock command
     */
    public function registerCommand() {
        $this->getServer()->getCommandMap()->register("", new SkyBlockCommand($this));
    }

    public function initialize() {
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder() . "islands")) {
            @mkdir($this->getDataFolder() . "islands");
        }
        if(!is_dir($this->getDataFolder() . "users")) {
            @mkdir($this->getDataFolder() . "users");
        }
        $this->saveDefaultConfig();
    }

}
