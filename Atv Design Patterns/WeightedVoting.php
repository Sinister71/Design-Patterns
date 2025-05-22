<?php
require_once 'VotingStrategy.php';

class WeightedVoting implements VotingStrategy {
    private int $weight;
    
    public function __construct(int $weight = 1) {
        $this->weight = max(1, min(5, $weight));
    }
    
    public function vote(array &$candidates, string $candidate): void {
        if (isset($candidates[$candidate])) {
            $candidates[$candidate] += $this->weight;
        }
    }
}