<?php

/* 
 * Copyright (C) 2017 RTGDaCoder
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace RTG\VPNBlocker;

/* Essentials */
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;

use pocketmine\utils\Config;

use pocketmine\event\player\PlayerPreLoginEvent;

class Loader extends PluginBase implements Listener {
    
    public $cfg;
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml");
        $number = count($this->cfg->get("blockedips"));
        $this->getLogger()->warning("$number Blocked IP's has been collected!");
    }
    
    public function onSave() {
        $this->cfg = new Config($this->getDataFolder() . "config.yml");
        $this->cfg->save();
    }
    
    public function onJoin(PlayerPreLoginEvent $e) {
        
        $p = $e->getPlayer();
        $n = $p->getName();
        $ip = $p->getAddress();
        $this->cfg = new Config($this->getDataFolder() . "config.yml");
            
            foreach($this->cfg->get("blockedips", []) as $list) {
                
                $find = explode(".", $ip);
                $check = strpos($list, $find);
                
                if($check != false) {
                    
                    $p->kick("[VPNBlocker] Please don't use a VPN Connection!");
                    $e->setCancelled();
                    
                }
                
            }
        
    }
    
    public function onDisable() {
        $this->onSave();
    }
    
}