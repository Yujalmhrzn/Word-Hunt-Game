"use strict";

/*
WORD SEARCH CONTROLLER
Handles game setup, themes, timer, AI logic, and dynamic grid size.
*/

function WordSearchController(
    gameId,
    listId,
    solveId,
    newGameId,
    themeId,
    timerId,
    gridSize,
    gamelevel   // ← THIS IS THE DIFFICULTY
) {

    // ---------------- THEMES ----------------
    var searchTypes = {
        "Arcade!🕹️":[["controller","pixels","algorithm","highscore"],["donkey kong","quarter","scoreboard","space invaders"],["tron","gameover","leaderboard","galaga"],["defender","powerup","sprite","frogger"],["joystick","asteriods","pacman","centipede"]],
        "Countries!🗺️":[["united states","canada","brazil","germany"],["france","italy","united kingdom","australia"],["south korea","mexico","argentina","spain"],["japan","china","india","russia"],["south africa","egypt","turkey","saudi arabia"]],
        "Disney!✨":[["mickey","minnie","cinderella","pixar"],["frozen","aladdin","little mermaid","goofy"],["donald duck","snow white","mulan","moana"],["jungle book","pocahontas","pinocchio","tangled"],["lion king","dumbo","jack sparrow","buzz lightyear"]],
        "Ocean Life!🪼":[["coral","dolphin","shark","octupus"],["reef","jellyfish","seahorse","whale"],["squid","seaweed","starfish","clam"],["mantee","lobster","crab","eel"],["manta ray","algae","tide","sea turtle"]],
        "Mystical Creatures!🐉":[["dragon","unicorn","griffin","phoenix"],["mermaid","centaur","hydra","kraken"],["goblin","siren","chimera","pegasus"],["gorgon","harpy","troll","yeti"],["sasquatch","banshee","leviathan","kitsune"]],
        "Sports!🏈":[["basketball","soccer","tennis","dance"],["baseball","track","volleyball","hockey"],["gymnastics","swimming","rugby","cricket"],["wrestling","cycling","golf","skiing"],["surfing","fencing","badminton","sailing"]],
        "Wildlife!🐘":[["tiger","elephant","panda","eagle"],["giraffe","lion","bear","kangaroo"],["wolf","zebra","leopard","rhino"],["cheetah","otter","penguin","fox"],["deer","bison","jaguar","sloth"]],
        "Colleges!🎓":[["LSU","Alabama","Clemson","Ohio State"],["Georgia","Oklahoma","Notre Dame","Princeton"],["Columbia","Duke","Auburn","Harvard"],["Vanderbilt","Penn State","Berkeley","Stanford"],["Cornell","Northwestern","Caltech","Yale"]],
        "Space Exploration!🚀":[["NASA","apollo","astronaut","moon"],["mars","rover","rocket","satellite"],["space shuttle","galaxy","telescope","solar system"],["milky way","black hole","comet","gravity"],["meteor","nebula","spacewalk","pluto"]],
        "Superheros!💪":[["batman","superman","wonder women","spiderman"],["iron man","thor","hulk","captain america"],["black panther","aquaman","wolverine","antman"],["deadpool","green lantern","doctor strange","hawkeye"],["rocket raccoon","black widow","cyclops","groot"]]
    };

    // ---------------- GAME VARIABLES ----------------
    var game;
    var view;
    var AIlogic;
    let timerInterval;
    let isFirstRound = true;
    let continueGame = true;

    // Expose game and view globally for button handlers
    window.wordSearchGame = game;
    window.wordSearchView = view;
    
    // Expose restart method globally
    window.restartWordSearch = function() {
        setUpWordSearch();
    };

    // ---------------- WORD COUNT BY LEVEL ----------------
    function getWordCountByLevel(level) {
        switch (level) {
            case "easy":
                return 6;
            case "medium":
                return 12;
            case "hard":
                return 20;
            default:
                return 12;
        }
    }

    // ---------------- GAME SETUP ----------------
    setUpWordSearch();

    function setUpWordSearch() {
    let wordsFound = [];
    let gameCompleted = false;

    // Clear existing board and word list
    $(gameId).empty();
    $(listId).empty();

    // Pick random theme
    var themes = Object.keys(searchTypes);
    var randTheme = themes[Math.floor(Math.random() * themes.length)];
    var themeGroups = searchTypes[randTheme];

    var flatWords = themeGroups.flat();
    flatWords.sort(() => Math.random() - 0.5);

    var wordCount = getWordCountByLevel(gamelevel);
    var selectedWords = flatWords.slice(0, wordCount);
    var finalWordList = selectedWords.map(w => [w.toUpperCase()]);

   function updateHeadings(theme) {
    $("#wordTheme").text(theme || "");
}

    game = new WordSearchLogic(gridSize, finalWordList);
    game.setUpGame();

    const boardEl = document.querySelector(gameId);
    if (boardEl) {
        let cellSize;
        if (gridSize <= 12) {
            cellSize = 40; // Easy: 12x12
        } else if (gridSize <= 16) {
            cellSize = 35; // Medium: 16x16  
        } else {
            cellSize = 27.5; // Hard: 20x20
        }
        boardEl.style.setProperty("--cell-size", `${cellSize}px`);
    }

    view = new WordSearchView(
        game.getMatrix(),
        game.getListOfWords(),
        gameId,
        listId,
        wordsFound,
        () => {
            if (!gameCompleted && wordsFound.length === game.getListOfWords().length) {
                gameCompleted = true;
                if (typeof window.onWordSearchGameComplete === "function") {
                    window.onWordSearchGameComplete();
                }
            }
        }
    );

    view.setUpView();
    view.triggerMouseDrag();

    // Update global references after initialization
    window.wordSearchGame = game;
    window.wordSearchView = view;
    
    // Set the theme display
    updateHeadings(randTheme);
}

}
