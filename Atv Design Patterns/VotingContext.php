<?php
require_once 'VotingStrategy.php';

class VotingContext {
    private VotingStrategy $strategy;
  
    public function __construct(VotingStrategy $strategy) {
        $this->strategy = $strategy;
    }
    
    public function setStrategy(VotingStrategy $strategy): void {
        $this->strategy = $strategy;
    }
    
    public function executeVoting(array &$candidates, string $candidate): void {
        $this->strategy->vote($candidates, $candidate);
    }
}