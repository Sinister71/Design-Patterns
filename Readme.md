# Sistema de Votação com PHP e Tailwind CSS

  

## Visão Geral

  

Este projeto implementa um sistema de votação utilizando PHP e Tailwind CSS, com foco na aplicação do padrão de projeto Strategy. O sistema permite que usuários votem em candidatos utilizando diferentes estratégias de contabilização de votos, demonstrando como o padrão Strategy pode ser aplicado em um cenário real.


## 📋 Índice

  

- [Requisitos](#requisitos)

- [Instalação](#instalação)

- [Estrutura do Projeto](#estrutura-do-projeto)

- [Padrão Strategy em Detalhe](#padrão-strategy-em-detalhe)

- [Conceito e Propósito](#conceito-e-propósito)

- [Implementação no Projeto](#implementação-no-projeto)

- [Diagrama de Classes](#diagrama-de-classes)

- [Benefícios no Contexto](#benefícios-no-contexto)

- [Componentes do Sistema](#componentes-do-sistema)

- [Interface VotingStrategy](#interface-votingstrategy)

- [Classe SimpleVoting](#classe-simplevoting)

- [Classe WeightedVoting](#classe-weightedvoting)

- [Classe VotingContext](#classe-votingcontext)

- [Interface do Usuário](#interface-do-usuário)

- [Fluxo de Execução](#fluxo-de-execução)

- [Exemplos de Uso](#exemplos-de-uso)

- [Considerações de Design](#considerações-de-design)


  

## 🔧 Requisitos

  

- PHP 7.4 ou superior

- Servidor web (Apache, Nginx, etc.)

- Navegador web moderno com suporte a JavaScript

- Conexão com internet (para carregar o Tailwind CSS via CDN)

  

## 🚀 Instalação

  

1. Clone este repositório ou baixe os arquivos:

```bash

git clone https://github.com/Sinister71/sistema-votacao-php.git
````

2. Coloque os arquivos em seu servidor web (ex: pasta htdocs do XAMPP ou www do WAMP)
3. Acesse o sistema através do navegador:

```plaintext
http://localhost/Design-Patterns/
```

4. Nenhuma configuração adicional é necessária, pois o sistema utiliza armazenamento em sessão PHP.


## 📁 Estrutura do Projeto

```
sistema-votacao-php/
├── index.php              # Arquivo principal com interface do usuário e lógica de controle
├── VotingStrategy.php     # Interface da estratégia de votação
├── SimpleVoting.php       # Implementação da votação simples
├── WeightedVoting.php     # Implementação da votação ponderada
├── VotingContext.php      # Classe de contexto que usa a estratégia
└── README.md              # Este arquivo
```

## 🧩 Padrão Strategy

### Conceito e Propósito

O padrão Strategy é um padrão de design comportamental que permite definir uma família de algoritmos, encapsular cada um deles e torná-los intercambiáveis. Este padrão permite que o algoritmo varie independentemente dos clientes que o utilizam.

**Elementos principais do padrão Strategy:**

1. **Interface Strategy**: Define um contrato comum para todas as estratégias concretas
2. **Estratégias Concretas**: Implementam diferentes variações do algoritmo
3. **Contexto**: Mantém uma referência a um objeto Strategy e delega a execução do algoritmo para este objeto


**Propósito no sistema de votação:**

- Permitir diferentes métodos de contabilização de votos
- Facilitar a adição de novos métodos sem modificar o código existente
- Permitir a troca dinâmica do método de votação durante a execução


### Implementação no Projeto

No contexto deste sistema de votação, o padrão Strategy é implementado da seguinte forma:

1. **Interface Strategy** (`VotingStrategy.php`):

1. Define o método `vote()` que todas as estratégias de votação devem implementar



2. **Estratégias Concretas**:

1. `SimpleVoting.php`: Implementa a estratégia de votação simples (1 voto)
2. `WeightedVoting.php`: Implementa a estratégia de votação ponderada (voto com peso)



3. **Contexto** (`VotingContext.php`):

1. Mantém uma referência à estratégia atual
2. Delega a operação de votação para a estratégia escolhida
3. Permite trocar a estratégia em tempo de execução



### Benefícios no Contexto

1. **Extensibilidade**: Novas estratégias de votação podem ser adicionadas facilmente implementando a interface `VotingStrategy`
2. **Encapsulamento**: Cada algoritmo de votação é encapsulado em sua própria classe
3. **Substituição em tempo de execução**: O sistema permite que o usuário escolha a estratégia de votação no momento do voto
4. **Manutenção simplificada**: Modificações em uma estratégia não afetam outras partes do sistema
5. **Princípio Open/Closed**: O sistema está aberto para extensão, mas fechado para modificação


## 📝 Componentes do Sistema

### Interface VotingStrategy

A interface `VotingStrategy` define o contrato que todas as estratégias de votação devem seguir:

```php
interface VotingStrategy {
    /**
     * Executa a votação para um candidato específico
     * 
     * @param array &$candidates Array de candidatos e seus votos
     * @param string $candidate Nome do candidato a receber o voto
     * @return void
     */
    public function vote(array &$candidates, string $candidate): void;
}
```

Esta interface define um único método `vote()` que recebe:

- Um array de referência contendo os candidatos e seus votos atuais
- O nome do candidato que receberá o voto


### Classe SimpleVoting

A classe `SimpleVoting` implementa a estratégia de votação simples, onde cada voto tem peso 1:

```php
class SimpleVoting implements VotingStrategy {
    /**
     * Executa uma votação simples (1 voto)
     * 
     * @param array &$candidates Array de candidatos e seus votos
     * @param string $candidate Nome do candidato a receber o voto
     * @return void
     */
    public function vote(array &$candidates, string $candidate): void {
        if (isset($candidates[$candidate])) {
            $candidates[$candidate]++;
        }
    }
}
```

Esta implementação simplesmente incrementa em 1 o contador de votos do candidato escolhido.

### Classe WeightedVoting

A classe `WeightedVoting` implementa a estratégia de votação ponderada, onde cada voto tem um peso específico:

```php
class WeightedVoting implements VotingStrategy {
    private int $weight;
    
    /**
     * Construtor
     * 
     * @param int $weight Peso do voto
     */
    public function __construct(int $weight = 1) {
        // Garantir que o peso esteja entre 1 e 5
        $this->weight = max(1, min(5, $weight));
    }
    
    /**
     * Executa uma votação ponderada (com peso)
     * 
     * @param array &$candidates Array de candidatos e seus votos
     * @param string $candidate Nome do candidato a receber o voto
     * @return void
     */
    public function vote(array &$candidates, string $candidate): void {
        if (isset($candidates[$candidate])) {
            $candidates[$candidate] += $this->weight;
        }
    }
}
```

Esta implementação:

- Recebe um peso no construtor (limitado entre 1 e 5)
- Incrementa o contador de votos do candidato pelo valor do peso


### Classe VotingContext

A classe `VotingContext` atua como o contexto no padrão Strategy:

```php
class VotingContext {
    private VotingStrategy $strategy;
    
    /**
     * Construtor
     * 
     * @param VotingStrategy $strategy Estratégia de votação a ser usada
     */
    public function __construct(VotingStrategy $strategy) {
        $this->strategy = $strategy;
    }
    
    /**
     * Define uma nova estratégia
     * 
     * @param VotingStrategy $strategy Nova estratégia de votação
     * @return void
     */
    public function setStrategy(VotingStrategy $strategy): void {
        $this->strategy = $strategy;
    }
    
    /**
     * Executa a votação usando a estratégia atual
     * 
     * @param array &$candidates Array de candidatos e seus votos
     * @param string $candidate Nome do candidato a receber o voto
     * @return void
     */
    public function executeVoting(array &$candidates, string $candidate): void {
        $this->strategy->vote($candidates, $candidate);
    }
}
```

Esta classe:

- Mantém uma referência à estratégia atual
- Permite trocar a estratégia através do método `setStrategy()`
- Delega a execução da votação para a estratégia através do método `executeVoting()`


### Interface do Usuário

O arquivo `index.php` contém a interface do usuário e a lógica de controle:

1. **Inicialização**:

1. Inicia a sessão PHP
2. Inicializa a lista de candidatos se não existir



2. **Processamento de Votos**:

1. Verifica se um voto foi submetido
2. Determina qual estratégia usar com base na seleção do usuário
3. Cria o contexto com a estratégia apropriada
4. Executa a votação



3. **Interface de Usuário**:

1. Formulário para selecionar candidato
2. Opção para escolher a estratégia de votação
3. Campo para definir o peso do voto (para votação ponderada)
4. Exibição dos resultados com barras de progresso
5. Botão para reiniciar a votação





## 🔄 Fluxo de Execução

1. **Inicialização**:

1. O usuário acessa o sistema
2. O sistema inicializa a sessão e os dados dos candidatos



2. **Seleção e Votação**:

1. O usuário seleciona um candidato
2. O usuário escolhe a estratégia de votação (simples ou ponderada)
3. Se escolher ponderada, o usuário define o peso do voto
4. O usuário submete o voto



3. **Processamento**:

1. O sistema identifica a estratégia escolhida
2. O sistema cria um objeto da estratégia apropriada
3. O sistema cria um contexto com a estratégia
4. O sistema executa a votação através do contexto
5. Os resultados são atualizados na sessão



4. **Exibição**:

1. O sistema exibe os resultados atualizados
2. O usuário pode votar novamente ou reiniciar a votação





## 📊 Exemplos de Uso

### Exemplo 1: Votação Simples

1. O usuário seleciona "Candidato A"
2. O usuário escolhe "Votação Simples"
3. O usuário clica em "Votar"
4. O sistema cria uma instância de `SimpleVoting`
5. O sistema cria um `VotingContext` com essa estratégia
6. O sistema executa `executeVoting()` no contexto
7. O `VotingContext` delega para `SimpleVoting::vote()`
8. O contador de votos do "Candidato A" é incrementado em 1


### Exemplo 2: Votação Ponderada

1. O usuário seleciona "Candidato B"
2. O usuário escolhe "Votação Ponderada"
3. O usuário define o peso como 3
4. O usuário clica em "Votar"
5. O sistema cria uma instância de `WeightedVoting` com peso 3
6. O sistema cria um `VotingContext` com essa estratégia
7. O sistema executa `executeVoting()` no contexto
8. O `VotingContext` delega para `WeightedVoting::vote()`
9. O contador de votos do "Candidato B" é incrementado em 3


## 🧠 Considerações de Design

### Por que usar o padrão Strategy?

1. **Flexibilidade**: O sistema precisa suportar diferentes algoritmos de votação
2. **Extensibilidade**: Novos algoritmos podem ser adicionados no futuro
3. **Encapsulamento**: Cada algoritmo tem sua própria lógica isolada
4. **Substituição dinâmica**: O usuário pode escolher o algoritmo em tempo de execução


### Alternativas Consideradas

1. **Condicionais simples**: Usar `if/else` para diferentes tipos de votação

1. Problema: Código menos organizado e mais difícil de estender



2. **Herança**: Criar subclasses de uma classe base de votação

1. Problema: Menos flexível para trocar comportamentos em tempo de execução



3. **Funções anônimas**: Usar callbacks para diferentes estratégias

1. Problema: Menos estruturado e mais difícil de manter





### Decisões de Implementação

1. **Uso de sessão PHP**: Para simplicidade, o sistema armazena os votos em sessão

1. Em um sistema real, seria usado um banco de dados



2. **Limite de peso (1-5)**: Para evitar manipulação, o peso é limitado

1. Isso demonstra validação e sanitização de entrada



3. **Interface web simples**: Foco na demonstração do padrão, não na interface

1. Tailwind CSS via CDN para estilização rápida e responsiva
