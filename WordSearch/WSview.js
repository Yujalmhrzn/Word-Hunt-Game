"use strict";

function WordSearchView(matrix, list, gameId, listId, wordsFound, onComplete)
{
  "use strict";

  var selfSolved = true;

  var names = {
    cell: "cell",
    pivot: "pivot",
    selectable: "selectable",
    selected: "selected",
    path: "path",
  };

  var select = {
    cells: "." + names.cell,
    pivot: "#" + names.pivot,
    selectable: "." + names.selectable,
    selected: "." + names.selected,
  };

  var searchGrid = {
    row: "row",
    column: "column",
  };

  this.setUpView = function () {
    createSearchGrid(
      matrix,
      names.cell,
      searchGrid.row,
      searchGrid.column,
      gameId
    );
    createListOfWords(list, listId);
  };

  function createSearchGrid(matrix, cellName, rowAttr, colAttr, boardId) {
    for (var i = 0; i < matrix.length; i++) {
      var row = $("<div/>");
      row.attr({ class: "boardRow" });
      for (var j = 0; j < matrix[i].length; j++) {
        var letter = $("<button/>");
        letter
          .attr({
            class: cellName,
            [rowAttr]: i,
            [colAttr]: j,
          })
          .text(matrix[i][j]);
        letter.appendTo(row);
      }
      row.appendTo($(boardId));
    }
  }

  function createListOfWords(wordList, wordListId) {
    for (var i = 0; i < wordList.length; i++) {
      var row = $("<div/>");
      row.attr({ class: "listRow" });
      for (var j = 0; j < wordList[i].length; j++) {
        var word = $("<li/>");
        word.attr({
          class: "listWord",
          text: wordList[i][j].replace(/\W/g, ""),
        });
        word.text(wordList[i][j]);
        word.appendTo(row);
      }
      row.appendTo($(wordListId));
    }
  }

  this.solve = function (wordLoc, matrix) {
    Object.keys(wordLoc).forEach(function (word) {
      var p = wordLoc[word].p;
      var startX = wordLoc[word].x;
      var startY = wordLoc[word].y;
      for (
        var k = 0, x = startX, y = startY;
        k < word.length;
        k++, x = incr[p](x, y).x, y = incr[p](x, y).y
      ) {
        $(select.cells + "[row = " + x + "][column = " + y + "]").addClass(
          "lost"
        );
      }
      selfSolved = false;
      validWordMade(list, word);
    });
  };

  this.triggerMouseDrag = function () {
    var selectedLetters = [];
    var wordMade = "";
    var mouseIsDown = false;

    $(select.cells).mousedown(function () {
      mouseIsDown = true;
      $(this).addClass(names.selected);
      $(this).attr({ id: names.pivot });
      highlightValidDirections($(this), matrix, names.selectable);
    });

    $(select.cells).mouseenter(function () {
      if (mouseIsDown && $(this).hasClass(names.selectable)) {
        var currentDirection = $(this).attr(names.path);
        for (var i = 0; i < selectedLetters.length; i++) {
          selectedLetters[i].removeClass(names.selected);
        }
        selectedLetters = [];
        wordMade = "";
        var cells = selectCellRange(
          select.cells,
          $(this),
          names.path,
          currentDirection,
          selectedLetters,
          wordMade
        );
        wordMade = cells.word;
        selectedLetters = cells.array;
      }
    });

    $(select.cells).mouseup(function () {
      endMove();
    });

    $(gameId).mouseleave(function () {
      if (mouseIsDown) {
        endMove();
      }
    });

    function endMove() {
      mouseIsDown = false;
      if (validWordMade(list, wordMade)) {
        $(select.selected).addClass("found");
      }
      $(select.selected).removeClass(names.selected);
      $(select.cells).removeAttr(names.path);
      $(select.pivot).removeAttr("id");
      $(select.selectable).removeClass(names.selectable);
      wordMade = "";
      selectedLetters = [];
    }
  };

  function highlightValidDirections(selectedCell, matrix, makeSelectable) {
    var cellRow = parseInt(selectedCell.attr(searchGrid.row));
    var cellCol = parseInt(selectedCell.attr(searchGrid.column));
    Object.keys(paths).forEach(function (path) {
      makeRangeSelectable(
        cellRow,
        cellCol,
        matrix.length,
        paths[path],
        makeSelectable
      );
    });
  }

  function makeRangeSelectable(x, y, l, p, selectable) {
    for (
      var i = incr[p](x, y).x, j = incr[p](x, y).y;
      bounds[p](i, j, l);
      i = incr[p](i, j).x, j = incr[p](i, j).y
    ) {
      $(
        "[" +
          searchGrid.row +
          "= " +
          i +
          "][" +
          searchGrid.column +
          "= " +
          j +
          "]"
      )
        .addClass(selectable)
        .attr({ [names.path]: p });
    }
  }

  function selectCellRange(
    cellsSelector,
    hoveredCell,
    pathAttr,
    path,
    selectedCells,
    wordConstructed
  ) {
    var hoverIndex;
    var pivotIndex;
    var cellRange = cellsSelector + "[" + pathAttr + " =" + path + "]";

    switch (path) {
      case paths.vert:
      case paths.horizon:
      case paths.priDiag:
      case paths.secDiag:
        hoverIndex = hoveredCell.index(cellRange) + 1;
        pivotIndex = 0;
        wordConstructed = $(select.pivot).text();
        wordConstructed = selectLetters(
          selectedCells,
          wordConstructed,
          cellRange,
          pivotIndex,
          hoverIndex
        );
        break;

      case paths.vertBack:
      case paths.horizonBack:
      case paths.priDiagBack:
      case paths.secDiagBack:
        hoverIndex = hoveredCell.index(cellRange);
        pivotIndex = $(cellRange).length;
        wordConstructed += selectLetters(
          selectedCells,
          wordConstructed,
          cellRange,
          hoverIndex,
          pivotIndex
        );
        wordConstructed += $(select.pivot).text();
        break;
    }

    return { word: wordConstructed, array: selectedCells };
  }

  function selectLetters(
    selectedCells,
    wordConstructed,
    range,
    lowerIndex,
    upperIndex
  ) {
    $(range)
      .slice(lowerIndex, upperIndex)
      .each(function () {
        $(this).addClass(names.selected);
        selectedCells.push($(this));
        wordConstructed += $(this).text();
      });
    return wordConstructed;
  }

 function validWordMade(list, wordToCheck) {
  for (var i = 0; i < list.length; i++) {
    for (var j = 0; j < list[i].length; j++) {
      var trimmedWord = list[i][j].replace(/\W/g, "");
      if (
        wordToCheck == trimmedWord ||
        wordToCheck == reversedWord(trimmedWord)
      ) {
        if (!wordsFound.includes(trimmedWord)) {
          wordsFound.push(trimmedWord);
        }

        $(".listWord[text=" + trimmedWord + "]").addClass("found");

        // Check if all words are found
        if (wordsFound.length === list.flat().length) {
          if (typeof onComplete === "function") {
            onComplete();
          }
        }
        return true;
      }
    }
  }
}



  function reversedWord(word) {
    var reversedWord = "";
    for (var i = word.length - 1; i >= 0; i--) {
      reversedWord += word.charAt(i);
    }
    return reversedWord;
  }
}
