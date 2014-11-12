<?php

namespace Alyosha\Modules\Chatbot;

use Alyosha\Modules\IRC\IrcEvent;
use Alyosha\Core\Event;

class ChatModule
{
	private $brain = [];

	private $events = [];

	public function fire()
	{}

	public function getTriggers()
	{
		return [
			'irc.message' => "compute"
		];
	}

	public function getEvents(){
		$evts = $this->events;
		$this->events = [];
		return $evts;
	}
	public function notify(Event $event){
		if ($event->getName() == "irc.message"){
			$this->compute($event);
		}
	}

	public function compute(Event $event) {
		$words = explode(" ", $event->getMessage());

		if (count($words) < 2) return;
		// pop off the first word
		$word = $words[0];

		// this loop treads the sentence as a chain of words, where $word -> $next_word
		for ( $i=1; $i < count($words); $i++ ) {
			$next_word = $words[$i];

			// if our marky bot hasn't experienced this word yet, initialize it's array
			if ( ! array_key_exists($word, $this->brain) ) $this->brain[$word] = array();

			// learn the word!
			$this->brain[$word][] = $next_word;

			$word = $next_word;
		}

		// This part adds an "end of sentence" marker. We treat it kind of like a word. 
		if ( ! array_key_exists($word, $this->brain) ) $this->brain[$word] = array();
		$this->brain[$word][] = ".";

		////////////
		// Generate a Markov Chain based on a random word from the sentence
 
		$word = $words[array_rand($words)];
		$chain = array();

		while ( $word != "." ) {
			// Pull a random word out of the list of words our Marky bot has seen follow our given word
			// in the past. We know $brain[$word] will always exist because we create everything in $words
			// in the learning phase.

			$possible_next_words = $this->brain[$word];

			$word = $possible_next_words[array_rand($possible_next_words)];
			print "chosen word: $word\n";
			$chain[] = $word;
		}

		$response = implode(" ", $chain);
		$responseEvent = new IrcEvent();
		$responseEvent->setName("irc.publish");
		$responseEvent->setChannel($event->getChannel());
		$responseEvent->setMessage($response);
		$this->events[] = $responseEvent;
	}
}
