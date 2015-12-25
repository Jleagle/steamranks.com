$('.swipebox').swipebox();

// Init tooltips
$('[data-toggle="tooltip"]').tooltip();

// Show all games button
$('#show-all-games').on(
  'click', function ()
  {
    $(this).remove();
    $('div.game-div').removeClass('hidden');
    $('img[data-src]').each(
      function ()
      {
        var src = $(this).attr('data-src');
        $(this).attr('src', src).removeAttr('data-src');
      }
    )
  }
);

// Make all cells the same height to fix floats

$(window).load(
  function ()
  {
    $(".row.row-eq-height").each(
      function (i, element)
      {
        var max = 10;
        // Find all cells in this row
        $(element).find("div[class^='col-']").each(
          function (i, element2)
          {
            max = Math.max(max, $(element2).height());
          }
        );

        $(element).find("div[class^='col-']").height(max);
      }
    );
  }
);

//  Hide panels
$(".panel[id] .close").closest('.panel-heading').on(
  'click', function ()
  {
    $body = $(this).parent().find(".panel-body");
    id = $body.parent().attr('id');

    if ($body.is(":visible"))
    {
      $body.hide();
      Cookies.set('panel-' + id, 0, {expires: 28, path: ''});
    }
    else
    {
      $body.show();
      Cookies.set('panel-' + id, 1, {expires: 28, path: ''});
    }
  }
);

// Hide/Show panels based on cookie
$(".panel[id] .close").each(
  function (i, obj)
  {
    var $panel = $(obj).closest('.panel');
    var id = $panel.attr('id');
    var cookie = Cookies.get('panel-' + id);

    if (cookie == 0 || cookie === undefined)
    {
      $panel.find('.panel-body').hide();
    }
  }
);

// Link table rows
$('tr[data-link]').on(
  'click', function ()
  {
    window.location.href = $(this).data('link');
  }
);

// Replace broken images
$(".game-div img").error(
  function ()
  {
    $(this).unbind("error").attr(
      "src",
      "/img/no-app-image.jpg"
    );
  }
);

// Drop down hovers
$('.dropdown-toggle').dropdownHover(
  {
    delay:                0,
    instantlyCloseOthers: true,
    hoverDelay:           0
  }
);

// Sticky table header
var $table = $('table.table-sticky');
$table.floatThead(
  {
    top: 51
  }
);
$table.on(
  "floatThead", function (e, isFloated, $floatContainer)
  {
    if (isFloated)
    {
      $floatContainer.addClass("floated");
    }
    else
    {
      $floatContainer.removeClass("floated");
    }
  }
);

// Scroll to level
var scrollTo = $(".scrollTo");
if (scrollTo.length)
{
  $('html, body').animate({scrollTop: scrollTo.offset().top - 81}, 'slow');
}

// Scroll to top link
$toTop = $(".toTop");
$(window).on(
  'scroll', function ()
  {
    if ($(this).scrollTop() >= 2000)
    {
      $toTop.addClass("in");
    }
    else
    {
      $toTop.removeClass("in");
    }
  }
);
$toTop.click(
  function ()
  {
    $('html, body').animate({scrollTop: 0}, 'slow');
  }
);

var canLoadRows = true;

// Users AJAX
function loadRows($tbody, sort, direction, event)
{
  if (canLoadRows)
  {
    canLoadRows = false;

    if (direction == 'down')
    {
      window.bottomPage = +window.bottomPage + 1;
      page = window.bottomPage;
    }
    else if (direction == 'up')
    {
      window.topPage = +window.topPage - 1;
      page = window.topPage;
    }
    else
    {
      page = window.topPage;
    }

    $.ajax(
      {
        url:      '/users/ajax?p=' + page + '&s=' + sort + '&d=' + direction,
        cache:    true,
        method:   'post',
        dataType: 'html'
      }
    ).success(
      function (data)
      {
        //pageTracker._trackPageview('/users/' + sort + '/' + page);
        if (direction == 'down')
        {
          // Remove the 'previous page' link
          var $data = $(data).not('tr.loadPrevPage');

          $tbody.append($data);
        }
        else if (direction == 'up')
        {
          // Keep the window in the correct position
          var pos = $(window).scrollTop();
          pos = pos + (37 * 50); // 37px * 50 rows

          $tbody.prepend(data);
          $('html, body').animate({scrollTop: pos}, 0);
          event.target.remove();
        }
        else
        {
          $tbody.append(data);
        }
        canLoadRows = true;
      }
    );
  }
}

$tbody = $("tbody[data-p][data-o]");

if ($tbody.length)
{
  var page = $tbody.attr('data-p');
  var sort = $tbody.attr('data-o');

  window.topPage = page;
  window.bottomPage = page;

  loadRows($tbody, sort, '', null);

  // On click up
  $tbody.on(
    'click', ".loadPrevPage", function (event)
    {
      //$(".loadPrevPage").remove();
      loadRows($tbody, sort, 'up', event);
    }
  );

  // On scroll down
  $(window).on(
    'scroll', function (event)
    {
      if ($(window).scrollTop() + $(window).height() > $(
          document
        ).height() - 100)
      {
        loadRows($tbody, sort, 'down', event);
      }
    }
  );
}
