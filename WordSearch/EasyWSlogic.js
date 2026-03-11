"use strict";

/*
EASY LEVEL WORD SEARCH LOGIC
- Grid size: 10x10
- Words placed only LEFT → RIGHT
- No diagonal or backward words
- Works with 2D array of words
*/

function EasyWordSearchLogic(wordList2D) {
  const size = 10; // 10x10 grid
  let matrix = [];
  let wordLocations = {};

  // --- Initialize empty grid ---
  function createMatrix() {
    for (let i = 0; i < size; i++) {
      matrix[i] = new Array(size).fill("");
    }
  }

  // --- Place words horizontally ---
  function placeWords() {
    wordList2D.forEach((rowWords) => {
      rowWords.forEach((wordRaw) => {
        const word = wordRaw.replace(/\s/g, "").toUpperCase();
        let placed = false;

        while (!placed) {
          let row = getRandom(size);
          let col = getRandom(size - word.length); // ensure fits

          // check if space is free
          let fits = true;
          for (let i = 0; i < word.length; i++) {
            if (matrix[row][col + i] !== "") {
              fits = false;
              break;
            }
          }

          if (fits) {
            for (let i = 0; i < word.length; i++) {
              matrix[row][col + i] = word[i];
            }
            wordLocations[word] = { row, col, direction: "RIGHT" };
            placed = true;
          }
        }
      });
    });
  }

  // --- Fill empty cells with random letters ---
  function fillRandomLetters() {
    for (let i = 0; i < size; i++) {
      for (let j = 0; j < size; j++) {
        if (matrix[i][j] === "") {
          matrix[i][j] = String.fromCharCode(65 + Math.floor(Math.random() * 26));
        }
      }
    }
  }

  // --- Helper ---
  function getRandom(limit) {
    return Math.floor(Math.random() * limit);
  }

  // --- Public methods ---
  this.setUpGame = function () {
    createMatrix();
    placeWords();
    fillRandomLetters();
  };

  this.getMatrix = function () {
    return matrix;
  };

  this.getWordLocations = function () {
    return wordLocations;
  };

  // Returns the **same 2D array** (all words uppercase, no spaces)
  this.getListOfWords = function () {
    return wordList2D.map((rowWords) =>
      rowWords.map((word) => word.replace(/\s/g, "").toUpperCase())
    );
  };
}
