function loadImgs(array_of_imgs){
	array_of_imgs.forEach(function(image_src){
		var img = new Image();
		img.src = image_src;
		console.log('image loaded');
	});

}


function startSlideshow(dom_element,array_of_imgs){
	loadImgs(array_of_imgs);
	console.log('All images loaded');
	var img_counter = 0;
	doSlideshow(dom_element,array_of_imgs,img_counter);
}

function doSlideshow(dom_element,array_of_imgs,img_counter){
	console.log(img_counter);
    if(img_counter >= array_of_imgs.length){
    	img_counter=0;
    }
	dom_element.css("background-image", "url("+array_of_imgs[img_counter++]+")").fadeIn(15000,function(){
    	setTimeout(function() {
		    doSlideshow(dom_element,array_of_imgs,img_counter);
		}, 5000);
    });
}