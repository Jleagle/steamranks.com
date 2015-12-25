var Loader = (function ()
{
  var canLoadRows = true;
  var pageTop = 1;
  var pageBottom = 1;
  var sort = null;
  var tbody = null;

  var init = function ()
  {
    setTableBody($tbody);
    incBottom();
    loadRows();
  };

  var setTableBody = function ($tbody)
  {
    tbody = $tbody;

    setPage(tbody.attr('data-p'));
    setSort(tbody.attr('data-o'));
  };

  var setPage = function (page)
  {
    pageTop = page;
    pageBottom = page;
  };

  var setSort = function (sortx)
  {
    sort = sortx;
  };

  var isNearBottom = function ()
  {
    var scrollTop = $(window).scrollTop();
    var windowHeight = $(window).height();
    var docHeight = $(document).height();

    return scrollTop + windowHeight > docHeight - 100;
  };

  var incTop = function ()
  {
    pageTop = +pageTop + 1;
  };

  var incBottom = function ()
  {
    pageBottom = +pageBottom + 1;
  };

  var loadRows = function ()
  {

  };

  return {
    init: init
  };
})();

$tbody = $("tbody[data-p][data-o]");

if ($tbody.length)
{
  Loader.init($tbody);
}
