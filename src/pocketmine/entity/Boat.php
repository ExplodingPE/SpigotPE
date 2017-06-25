<?php
<<<<<<< HEAD
namespace pocketmine\entity;

use pocketmine\item\Item as ItemItem;
use pocketmine\level\Level;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
=======

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://itxtech.org
 *
 */

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item as ItemItem;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
>>>>>>> master
use pocketmine\Player;

class Boat extends Vehicle{
	const NETWORK_ID = 90;
<<<<<<< HEAD
	
	public $height = 0.7;
	public $width = 1.6;
	public $gravity = 0.5;
	public $drag = 0.1;
	protected $maxHealth = 4;
    
	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->woodID)){
			$nbt->woodID = new ByteTag("woodID", 0);
		}
		parent::__construct($level, $nbt);
	}
	
	public function initEntity(){
		parent::initEntity();
=======

	public $height = 0.7;
	public $width = 1.6;

	public $gravity = 0.5;
	public $drag = 0.1;

	public function __construct(Level $level, CompoundTag $nbt){
		if(!isset($nbt->WoodID)){
			$nbt->WoodID = new IntTag("WoodID", 0);
		}
		parent::__construct($level, $nbt);
		$this->setDataProperty(self::DATA_VARIANT, self::DATA_TYPE_INT, $this->getWoodID());
	}

	public function getWoodID() : int{
		return (int) $this->namedtag["WoodID"];
>>>>>>> master
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
<<<<<<< HEAD
		$pk->type = self::NETWORK_ID;
		$pk->entityRuntimeId = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
=======
		$pk->eid = $this->getId();
		$pk->type = Boat::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = 0;
		$pk->speedY = 0;
		$pk->speedZ = 0;
		$pk->yaw = 0;
		$pk->pitch = 0;
>>>>>>> master
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
<<<<<<< HEAD
	
	public function getWoodID(){
		return $this->namedtag["woodID"];
	}
		
	/*public function onUpdate($currentTick){
		if($this->isAlive()){
			$this->timings->startTiming();
			$hasUpdate = false;
			
			if($this->isInsideOfWater()){
				$hasUpdate = true;
				$this->move(0,0.1, 0);
				$this->updateMovement();
			}
			if($this->isLinked() && $this->getlinkedTarget() !== null){
				if(($player = $this->getlinkedTarget()) instanceof Player){
					$newyaw = $player->getYaw();
					$deltayaw = $newyaw - $this->getYaw();
					if($deltayaw < 0.1) $deltayaw * -1;
					$hasUpdate = $newyaw - $this->getYaw() > 0.1;
				}
				if($hasUpdate){
					$this->setRotation($newyaw, $this->pitch);
					$this->move(0, 1, 0);
				}
				$this->updateMovement();
			}
			if($this->getHealth() < $this->getMaxHealth()){
				$this->heal(0.1, new EntityRegainHealthEvent($this, 0.1, EntityRegainHealthEvent::CAUSE_CUSTOM));
				$hasUpdate = true;
			}
			
			$this->timings->stopTiming();

			return $hasUpdate;
		}
	}*/

	public function getDrops(){
		return [
			ItemItem::get(ItemItem::BOAT, $this->getWoodID(), 1)
		];
	}
=======

	public function attack($damage, EntityDamageEvent $source){
		parent::attack($damage, $source);

		if(!$source->isCancelled()){
			$pk = new EntityEventPacket();
			$pk->eid = $this->id;
			$pk->event = EntityEventPacket::HURT_ANIMATION;
			foreach($this->getLevel()->getPlayers() as $player){
				$player->dataPacket($pk);
			}
		}
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}
		$tickDiff = $currentTick - $this->lastUpdate;
		if($tickDiff <= 0 and !$this->justCreated){
			return true;
		}

		$this->lastUpdate = $currentTick;

		$this->timings->startTiming();

		$hasUpdate = $this->entityBaseTick($tickDiff);

		if(!$this->level->getBlock(new Vector3($this->x,$this->y,$this->z))->getBoundingBox()==null or $this->isInsideOfWater()){
			$this->motionY = 0.1;
		}else{
			$this->motionY = -0.08;
		}

		$this->move($this->motionX, $this->motionY, $this->motionZ);
		$this->updateMovement();

		if($this->linkedEntity == null or $this->linkedType = 0){
			if($this->age > 1500){
				$this->close();
				$hasUpdate = true;
				//$this->scheduleUpdate();

				$this->age = 0;
			}
			$this->age++;
		}else $this->age = 0;

		$this->timings->stopTiming();


		return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
	}


	public function getDrops(){
		return [
			ItemItem::get(ItemItem::BOAT, 0, 1)
		];
	}

	public function getSaveId(){
		$class = new \ReflectionClass(static::class);
		return $class->getShortName();
	}
>>>>>>> master
}