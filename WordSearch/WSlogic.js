"use strict";

/*
WORD SEARCH LOGIC
Responsible ONLY for:
- Creating the grid
- Placing given words
- Filling empty cells
*/

function WordSearchLogic(gridSize, list) {

  // ---------------- BOARD SETUP ----------------
  var board = {
    matrix: [],
    size: gridSize || 16
  };

  var thisWord = {
    viablePaths: [],
    wordFitted: false
  };

  var wordLocations = {};

  // ---------------- GAME SETUP ----------------
  this.setUpGame = function () {
    board.matrix = createMatrix(board.size);
    fitWordsIntoMatrix(list, board.matrix);
    fillWithRandomLetters(board.matrix);
  };

  // ---------------- MATRIX ----------------
  function createMatrix(size) {
    var matrix = new Array(size);
    for (var i = 0; i < size; i++) {
      matrix[i] = new Array(size);
    }
    return matrix;
  }

  // ---------------- WORD FITTING ----------------
  function fitWordsIntoMatrix(wordList, matrix) {
    for (var i = 0; i < wordList.length; i++) {
      for (var j = 0; j < wordList[i].length; j++) {

        var trimmedWord = trimWord(wordList[i][j]);

        for (var k = 0; !thisWord.wordFitted && k < 100; k++) {
          insertWordIntoMatrix(trimmedWord, matrix);
        }

        if (!thisWord.wordFitted) {
          wordList[i].splice(j, 1);
          j--;
        } else {
          thisWord.wordFitted = false;
        }
      }
    }
  }

  function insertWordIntoMatrix(word, matrix) {
    var x = getRandomNum(matrix.length);
    var y = getRandomNum(matrix.length);

    if (!matrix[x][y] || matrix[x][y] === word.charAt(0)) {
      checkPossibleOrientations(word, matrix, x, y);
    }
  }

  function checkPossibleOrientations(w, m, x, y) {
    Object.keys(paths).forEach(p => {
      doesOrientationFit(w, m, x, y, paths[p]);
    });

    if (thisWord.viablePaths.length) {
      var finalPath =
        thisWord.viablePaths[getRandomNum(thisWord.viablePaths.length)];

      thisWord.viablePaths = [];
      wordLocations[w] = { x, y, p: finalPath };
      setWordIntoMatrix(w, m, x, y, finalPath);
    }
  }

  function setWordIntoMatrix(w, m, x, y, p) {
    for (
      var k = 0;
      k < w.length;
      k++, x = incr[p](x, y).x, y = incr[p](x, y).y
    ) {
      m[x][y] = w.charAt(k);
    }
    thisWord.wordFitted = true;
  }

  function doesOrientationFit(w, m, x, y, p) {
    var count = 0;

    for (
      var k = 0;
      k < w.length && bounds[p](x, y, m.length);
      k++, x = incr[p](x, y).x, y = incr[p](x, y).y
    ) {
      if (!m[x][y] || m[x][y] === w.charAt(k)) {
        count++;
      }
    }

    if (count === w.length) {
      thisWord.viablePaths.push(p);
    }
  }

  // ---------------- RANDOM FILL ----------------
  function fillWithRandomLetters(matrix) {
    for (var i = 0; i < matrix.length; i++) {
      for (var j = 0; j < matrix[i].length; j++) {
        if (!matrix[i][j]) {
          matrix[i][j] =
            String.fromCharCode(65 + Math.floor(Math.random() * 26));
        }
      }
    }
  }

  // ---------------- HELPERS ----------------
  function getRandomNum(n) {
    return Math.floor(Math.random() * n);
  }

  function trimWord(word) {
    return word.replace(/\W/g, "");
  }

  // ---------------- GETTERS ----------------
  this.getMatrix = () => board.matrix;
  this.getWordLocations = () => wordLocations;
  this.getListOfWords = () => list;
}
