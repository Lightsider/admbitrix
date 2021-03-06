;(function() {
	"use strict";

	/**
	 * @todo Refactoring
	 */
	BX.addCustomEvent(window, "BX.Landing.Block:init", function(event) {
		var headerSelector = event.makeRelativeSelector('.u-header');
		if (event.block.querySelectorAll(headerSelector).length > 0)
		{
			var headers;

			if (BX.Landing.getMode() !== "view")
			{
				headers = [].slice.call(event.block.querySelectorAll(".u-header"));
				headers.forEach(function (header)
				{
					var subheader = [].slice.call(header.querySelectorAll(".u-header__section"));

					if (subheader.length)
					{
						subheader.forEach(function (item)
						{
							item.style.zIndex = "auto";
						});
					}

					header.style.zIndex = "auto";
					BX.removeClass(header, "u-header--sticky-top");
					BX.removeClass(header, "u-header--change-appearance");
				});
			}
			else
			{
				headers = [].slice.call(event.block.querySelectorAll(".u-header"));
				headers.forEach(function (header)
				{
					var subheader = [].slice.call(header.querySelectorAll(".u-header__section"));

					if (subheader.length)
					{
						subheader.forEach(function (item)
						{
							item.style.zIndex = null;
						});
					}

					header.style.zIndex = null;
					BX.addClass(header, "u-header--sticky-top");
					BX.addClass(header, "u-header--change-appearance");
				});
				$.HSCore.components.HSHeader.init($(".u-header"));
			}
		}

		var scrollNavSelector = event.makeRelativeSelector('.js-scroll-nav');
		if (event.block.querySelectorAll(scrollNavSelector).length > 0)
		{
			$.HSCore.components.HSScrollNav.init($('.js-scroll-nav'), {
				duration: 400,
				easing: 'easeOutExpo'
			});
		}
	});


	//unset ACTIVE on menu link
	BX.addCustomEvent("BX.Landing.Block:Card:add", function (event)
	{
		var headerSelector = event.makeRelativeSelector('.u-header');
		if (event.block.querySelectorAll(headerSelector).length > 0)
		{
			if (event.card && BX.hasClass(event.card, 'active'))
			{
				BX.removeClass(event.card, 'active');
			}
		}
	});
})();