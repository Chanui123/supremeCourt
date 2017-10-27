(function ($) {

  var app = $.sammy('#app', function () {
    this.use('Template');

    this.get('#/', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/home.html', {})
        .appendTo(context.$element());
    });

    this.get('#/purpose', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/purpose.html', {})
        .appendTo(context.$element());
    });

    this.get('#/login', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/login.html', {})
        .appendTo(context.$element());
    });

    this.get('#/register', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/register.html', {})
        .appendTo(context.$element());
    });

    this.get('#/sui', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/sui.html', {})
        .appendTo(context.$element());
    });

    this.get('#/criminal', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/criminal.html', {})
        .appendTo(context.$element());
    });

    this.get('#/civil', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/civil.html', {})
        .appendTo(context.$element());
    });

    this.get('#/register', function (context) {
      var str = location.href.toLowerCase();
      context.app.swap('');
      context.render('templates/register.html', {})
        .appendTo(context.$element());
    });



    this.before('.*', function () {

      var hash = document.location.hash;
      $("nav").find("a").removeClass("current");
      $("nav").find("a[href='" + hash + "']").addClass("current");
    });

  });

  $(function () {
    app.run('#/');
  });

})(jQuery);