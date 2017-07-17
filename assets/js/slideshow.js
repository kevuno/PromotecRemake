/** 
* Loads images given an array of strings that path the source of the images
**/
function loadImgs(array_of_imgs){
	array_of_imgs.forEach(function(image_src){
		var img = new Image();
		img.src = image_src;
	});

}

/**
* Begins the slidewhow, helpul to set up the images and a counter
* @param dom_element: The element to which the background-img property will be set
* @param array_of_imgs: The  array of strings that path the source of the images
*/
function startSlideshow(dom_element,array_of_imgs){
	loadImgs(array_of_imgs);
	var img_counter = 0;
	var duration = 10000; // Duration of one image in miliseconds
	doSlideshow(dom_element,array_of_imgs,img_counter,duration);
}
/** Does one slideshow that lasts a given duration
* @param dom_element: The element to which the background-img property will be set
* @param array_of_imgs: The  array of strings that path the source of the images
* @param img_counter: A helper counter to perform an incremental recursion
* @param duration: The duration of each image
 **/
function doSlideshow(dom_element,array_of_imgs,img_counter, duration){
    if(img_counter >= array_of_imgs.length){
    	img_counter=0;
    }
	dom_element.css("background-image", "url("+array_of_imgs[img_counter++]+")").fadeIn(15000,function(){
    	setTimeout(function() {
		    doSlideshow(dom_element,array_of_imgs,img_counter,duration);
		}, duration);
    });
}