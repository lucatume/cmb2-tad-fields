define(function () {
  // Catalan
  return {
    errorLoading: function () {
      return 'La càrrega ha fallat';
    },
    inputTooLong: function (args) {
      var remainingChars = args.input.length - args.maximum;

      var message = 'Si us plau, elimina ' + remainingChars + ' car';

      if (remainingChars == 1) {
        message += 'àcter';
      } else {
        message += 'àcters';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remaningChars = args.minimum - args.input.length;

      var message = 'Si us plau, introdueix ' + remainingChars + ' car';

      if (remainingChars == 1) {
        message += 'àcter';
      } else {
        message += 'àcters';
      }

      return message;
    },
    loadingMore: function () {
      return 'Carregant més resultats…';
    },
    maximumSelection: function (args) {
      var message = 'Només es pot seleccionar ' + args.maximum + ' element';

      if (args.maximum != 1) {
        message += 's';
      }

      return message;
    },
    noResults: function () {
      return 'No s\'han trobat resultats';
    },
    searching: function () {
      return 'Cercant…';
    }
  };
});
