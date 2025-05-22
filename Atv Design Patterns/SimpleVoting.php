<?php
require_once 'VotingStrategy.php';
class SimpleVoting implements VotingStrategy {
    
    public function vote(array &$candidates, string $candidate): void {
        if (isset($candidates[$candidate])) {
            $candidates[$candidate]++;
        }
    }
}