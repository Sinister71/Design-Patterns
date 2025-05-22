<?php
interface VotingStrategy {
    
    public function vote(array &$candidates, string $candidate): void;
}