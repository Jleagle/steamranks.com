$(document).ready(
  function ()
  {
    var hash = document.location.hash;
    var prefix = "t-";
    if (hash)
    {
      $('.nav-tabs a[href=' + hash.replace(prefix, "") + ']').tab('show');
    }

    $('.nav-tabs a').on(
      'shown.bs.tab', function (e)
      {
        window.location.hash = e.target.hash.replace("#", "#" + prefix);
      }
    );
  }
);
