/**
 * Backend Lafka scripts
 */
(function ($) {
	"use strict";
	$(document).ready(function () {
		// Init wpColorPicker color picker for menu label colors
		$('input.lafka-menu-colorpicker').wpColorPicker();

        // Init wpColorPicker color picker for theme options
        $('input.lafka-theme-options-colorpicker').wpColorPicker({
            change: function(event, ui){
                $(this).closest('div.controls').find('div.lafka_font_preview p').css({color: ui.color});
			}
		});

		// Proper position featured images metaboxes
		var featured_img_meta = $('#postimagediv');
		var featured_imgs_arr = new Array();
		if (featured_img_meta.length) {
			for (var i = 6; i >= 2; i--) {
				featured_imgs_arr[i] = $('#lafka_featured_' + i);
				if (featured_imgs_arr[i].length) {
					featured_imgs_arr[i].detach().insertAfter(featured_img_meta);
				}
			}
		}

		// Proper position Foodmenu Gallery Options metabox
		var prtfl_gallery_options_meta = $('#lafka_foodmenu_cz');
		if (prtfl_gallery_options_meta.length && featured_img_meta.length) {
			prtfl_gallery_options_meta.detach().insertBefore(featured_img_meta);
		}

        // Proper position Product Gallery Type Options metabox
        var product_gallery_options_meta = $('#lafka_product_gallery_type');
		var product_gallery_meta = $('#woocommerce-product-images');
        if (product_gallery_options_meta.length && product_gallery_meta.length) {
            product_gallery_options_meta.detach().insertBefore(product_gallery_meta);
        }

		// Init fonticonpicker on menu edit
		$('#menu-to-edit a.item-edit').on('click', function () {
			$(this).parents("li.menu-item").find("input.lafka-menu-icons").fontIconPicker({
				source: ['flaticon-001-popcorn', 'flaticon-002-tea', 'flaticon-003-chinese-food', 'flaticon-004-tomato-sauce', 'flaticon-005-cola-1', 'flaticon-006-burger-2', 'flaticon-007-burger-1', 'flaticon-008-fried-potatoes', 'flaticon-009-coffee', 'flaticon-010-burger', 'flaticon-011-ice-cream-1', 'flaticon-012-cola', 'flaticon-013-milkshake', 'flaticon-014-sauces', 'flaticon-015-hot-dog-1', 'flaticon-016-chicken-leg-1', 'flaticon-017-croissant', 'flaticon-018-cheese', 'flaticon-019-sausage', 'flaticon-020-fried-egg', 'flaticon-021-fried-chicken', 'flaticon-022-serving-dish', 'flaticon-023-pizza-slice', 'flaticon-024-chef-hat', 'flaticon-025-meat', 'flaticon-026-ice-cream', 'flaticon-027-donut', 'flaticon-028-rice', 'flaticon-029-package', 'flaticon-030-kebab', 'flaticon-031-delivery', 'flaticon-032-food-truck', 'flaticon-033-waiter-1', 'flaticon-034-waiter', 'flaticon-035-taco', 'flaticon-036-chips', 'flaticon-037-soda', 'flaticon-038-take-away', 'flaticon-039-fork', 'flaticon-040-coffee-cup', 'flaticon-041-waffle', 'flaticon-042-beer', 'flaticon-043-chicken-leg', 'flaticon-044-pitcher', 'flaticon-045-coffee-machine', 'flaticon-046-noodles', 'flaticon-047-menu', 'flaticon-048-hot-dog', 'flaticon-049-breakfast', 'flaticon-050-french-fries']			});
		});

		if (lafka_back_js_params.new_orders_push_notifications === 'yes') {

				if (typeof Notification !== "undefined") {
					window.addEventListener('load', function () {

							Notification.requestPermission().then(function (result) {
								if (result === 'denied') {
									console.log('Permission wasn\'t granted. Allow a retry.');
									return;
								}

								if (result === 'default') {
									console.log('The permission request was dismissed.');
									return;
								}

								if ('serviceWorker' in navigator) {
									navigator.serviceWorker.register(lafka_back_js_params.service_worker_path).then(function (registration) {

										// Registration was successful
										setInterval(function () {
										$.ajax({
											type: 'POST',
											data: {
												action: 'lafka_new_orders_notification'
											},
											url: ajaxurl,
											success: function (response) {
												if (response != '') {
													var notifyTitle = response.title;
													var options = {
														body: response.body,
														icon: response.icon
													};

													if (isMobileOrTablet()) {
														// Show notification through worker for mobile
														registration.showNotification(notifyTitle, options);
													} else {
														var n = new Notification(notifyTitle, options);
														n.custom_options = {
															url: response.url,
														};
														n.onclick = function (event) {
															event.preventDefault(); // prevent the browser from focusing the Notification's tab
															window.open(n.custom_options.url, '_blank');
														};

														//add audio notify because, this property is not currently supported in any browser.
														$('<audio class="lafka_order_notify_audio" controls preload="auto" hidden="hidden">').attr({
															'src': response.sound,
														}).appendTo("body");
														$('.lafka_order_notify_audio').trigger("play");

														n.onclose = function (event) {
															event.preventDefault();
															$('.lafka_order_notify_audio').remove();
														};
													}
												}
											},
											complete: function () {
											}
										});
										}, 30000);
									}, function (err) {
										// registration failed :(
										console.log('ServiceWorker registration failed: ', err);
									});
								}
							});
					});
				}
		}
	});
})(window.jQuery);

window.isMobileOrTablet = function() {
	var check = false;
	(function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
	return check;
};