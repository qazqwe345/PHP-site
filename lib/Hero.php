<?php
class States{
	public $hp;
	public $mp;
	public $atk;
	public $def;
	public function __construct($sourceHp, $sourceMp, $sourceAtk, $sourceDef){
		$this->hp = $sourceHp;
		$this->mp = $sourceMp;
		$this->atk = $sourceAtk;
		$this->def = $sourceDef;
	}
}

class Skill{
	public $name;
	public $description;
	function __construct($sourceName, $sourceDescription){
		$this->name = $sourceName;
		$this->description = $sourceDescription;
	}
}

class Hero{
	public $name;
	public $states;
	public $skills;
	public $article;
	function __construct($sourceName, $sourceStates, $sourceSkills, $sourceArticle){
		$this->name = $sourceName;
		$this->states = $sourceStates;
		$this->skills = $sourceSkills;
		$this->article = $sourceArticle;
	}
}

$sourceStates = new States(100, 80, 40, 20);
$sourceSkills[] = new Skill("hunt","...");
$sourceSkills[] = new Skill("blood hunt","...");
$sourceSkills[] = new Skill("swear","...");
$sourceSkills[] = new Skill("bullet","...");
$sourceArticle = "...";
$hero = new Hero("Van",$sourceStates, $sourceSkills, $sourceArticle);

echo $hero->states->hp."</br>";
echo $hero->states->atk."</br>";
echo $hero->skills[0]->name."</br>";
echo $hero->article."</br>";

?>
