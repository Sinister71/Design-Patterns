<?php
require_once 'VotingStrategy.php';
require_once 'SimpleVoting.php';
require_once 'WeightedVoting.php';
require_once 'VotingContext.php';

session_start();

if (!isset($_SESSION['candidates'])) {
    $_SESSION['candidates'] = [
        'Candidato A' => 0,
        'Candidato B' => 0,
        'Candidato C' => 0
    ];
}

if (isset($_POST['vote']) && isset($_POST['candidate'])) {
    $candidate = $_POST['candidate'];
    $strategy = $_POST['strategy'] ?? 'simple';
    
    if ($strategy === 'weighted') {
        $weight = isset($_POST['weight']) ? (int)$_POST['weight'] : 1;
        $votingContext = new VotingContext(new WeightedVoting($weight));
    } else {
        $votingContext = new VotingContext(new SimpleVoting());
    }
    
    $votingContext->executeVoting($_SESSION['candidates'], $candidate);
}

if (isset($_POST['reset'])) {
    $_SESSION['candidates'] = [
        'Candidato A' => 0,
        'Candidato B' => 0,
        'Candidato C' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Votação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8 text-blue-600">Sistema de Votação</h1>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Votar</h2>
            
            <form method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Selecione um candidato:</label>
                    <div class="space-y-2">
                        <?php foreach (array_keys($_SESSION['candidates']) as $candidate): ?>
                            <div class="flex items-center">
                                <input type="radio" name="candidate" id="<?= $candidate ?>" 
                                       value="<?= $candidate ?>" class="mr-2" required>
                                <label for="<?= $candidate ?>" class="text-gray-700"><?= $candidate ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">Estratégia de votação:</label>
                    <select name="strategy" id="strategy" class="w-full p-2 border rounded" onchange="toggleWeightField()">
                        <option value="simple">Votação Simples</option>
                        <option value="weighted">Votação Ponderada</option>
                    </select>
                </div>
                
                <div id="weightField" class="hidden">
                    <label class="block text-gray-700 mb-2">Peso do voto (1-5):</label>
                    <input type="number" name="weight" min="1" max="5" value="1" 
                           class="w-full p-2 border rounded">
                </div>
                
                <button type="submit" name="vote" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Votar
                </button>
            </form>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Resultados</h2>
            
            <div class="space-y-4">
                <?php foreach ($_SESSION['candidates'] as $candidate => $votes): ?>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium"><?= $candidate ?>:</span>
                            <span class="font-medium"><?= $votes ?> votos</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <?php 
                            $total = array_sum($_SESSION['candidates']);
                            $percentage = $total > 0 ? ($votes / $total) * 100 : 0;
                            ?>
                            <div class="bg-blue-500 h-4 rounded-full" style="width: <?= $percentage ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <form method="post" class="mt-6">
                <button type="submit" name="reset" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded">
                    Reiniciar Votação
                </button>
            </form>
        </div>
    </div>
    
    <script>
        function toggleWeightField() {
            const strategy = document.getElementById('strategy').value;
            const weightField = document.getElementById('weightField');
            
            if (strategy === 'weighted') {
                weightField.classList.remove('hidden');
            } else {
                weightField.classList.add('hidden');
            }
        }
    </script>
</body>
</html>