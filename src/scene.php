
<link href="css/personnal.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">

<style>
	#playingGround{
		width: 70%;
		height: 100%;
		z-index: 1;
		position: absolute;
	}
	#playingGround img{
		position: absolute !important;
	}
	.ui-draggable-dragging{
		z-index:40 !important;
		padding-left: 20%;
	}
	#leftMenu{
		width: 15%;
		height: 100%;
		display: block;
		float: left;
		left: 0;
		z-index: 2;
		position: absolute;
		border-style: solid;
	}
	#list{
		width: 100%;
		height: 100%;
		overflow-y: auto !important; 
		padding: 0;
	}
	#list > .container{
		width: 100%;
		height: 50px;
		padding: 2%;
		vertical-align: middle;
		margin: 0;
		cursor: move;
		float: left;
		display: inline-block;
		position: relative;
		z-index: 2;
		border-bottom: black 2px solid;
	}
	.container > div{
		width: 80%;
		margin-right: 5%;
		display: inline;
		vertical-align: middle;
	}
	.container > img{
		display: inline;
		width: 10%;
		margin: 0;
		padding: 0;
		vertical-align: middle;
	}
	.help{
	    border : 1px solid #1877D5;
	    background : #84BEFD;
	    opacity : 0.3;
	}
	#rightMenu{
		display: inline-block;
		height: 100%;
		width: 15%;
		height: 100%;
		display:block;
		position: absolute;
		right: 0;
		z-index: 2;
		border-style: solid;
	}
	.selectedItem{
		background-color: yellow;
	}
	.selectedImage{
		z-index: 1000 !important;
	}
	#list2 > .container:hover{
		cursor: pointer;
	}
	.container-fluid{
		height: 100%;
		width: 100%;
		margin: 0;
		padding: 0;
		padding-left : 15%; 
		position: absolute;
	}
	#sizeInfo{
		position: absolute;
		bottom: 0;
		right: 0;
		z-index: 999999;
		opacity: 70%;
	}
	body{
		background-image: url(image/background.png);
		font-family: "Century Gothic",CenturyGothic,AppleGothic,sans-serif;
	}

	#navbar{
		border-style: solid;
	}
</style>

<div class="container-fluid">
	<!--<nav class="navbar navbar-default">
    	<div class="navbar-header">
      		<a class="navbar-brand" href="#">
        		<button type="button" class="btn btn-default navbar-btn">Reset</button>
      		</a>
    	</div>
	</nav>-->

	<div class="col-md-4" id="leftMenu">
		<div id="folderName">Ressources</div>
		<div id="list"></div>
	</div>
	<div class="col-md-12" id="playingGround">
		<div id=sizeInfo>Size</div>
	</div>
	<div class="col-md-4" id="rightMenu">
		<div id="list2">Images en cours</div>
	</div>
</div>

<script>
	const selectedFolder = "image/ressources";
	const zoomRatio = 1;
	var maxIndex = 0;
	var fileIntoSelectedFolder = new Array();
	var fileInPlayingGround = new Array();
	var selectedItem = "";
	var mouseX = 0;
	var mouseY = 0;
	var num = 1;
	var lbmPressed = false;
	var leftMenuWidth = ($( document ).width()/100)*15;

	$(document).on('contextmenu', function(event) {
		event.preventDefault();
	});

	//Set by interval the array fileIntoSelectedFolder and populate the object with id #list
	setInterval(function(){
		$.ajax({
			url: selectedFolder+"/",
			success: function(_data){
				$(_data).find("td > a").each(function(){
					if(openFile($(this).attr("href"))){
						if(fileIntoSelectedFolder.indexOf($(this).attr("href")) == -1){
							fileIntoSelectedFolder.push($(this).attr("href"));
							$('#leftMenu #list').append('<div class="container draggableItem"><div>'+$(this).attr("href")+'</div><img src="'+selectedFolder+'/'+$(this).attr("href")+'"></div>')
						}
					}
				})
			}
		});
		
	}, 2000);

	//Test if the file passed is a image and return true
	function openFile(file) {
		var extension = file.substr( (file.lastIndexOf('.') +1) );
	    switch(extension) {
	    	case 'jpg':
	        case 'png':
		    case 'gif':   // the alert ended with pdf instead of gif.
	    	 	return true;
	        	break;
	    	default:
	      		return false;
	    }
	};

	function addToList(objName, objId){
		fileInPlayingGround.push(objName);
		$('#rightMenu #list2').append('<div class="container"> <div id="list_'+objId+'">'+objName+' <img class="unlock" id="lock_' +objId+'" src="image/openLock.png"> <img class="delete" id="del_'+objId+'" src="image/delete.png"</div></div>');
	}

	//DRAG N DROP
	setInterval(function(){
			$('.draggableItem').draggable({
				constrainment : '#playingGround',
				cursor : 'move',
				stack : '.draggableItem',
				helper : 'clone',
				revert: true,
				revertDuration: 0
			});

			$('#playingGround').droppable({
				drop : function(event, ui){
					var current = ui.draggable.clone();
					var path = current.text();
					var num = Math.floor(Math.random() * 101)+Math.floor(Math.random() * 11);
					if($('#'+path+num).length){
						num = Math.floor(Math.random() * 101)+Math.floor(Math.random() * 11);
					}
					current.fadeOut(function(){
						var top = ui.offset.top-ui.draggable.height()*2;
						var left = ui.offset.left-ui.draggable.width()*2;
						maxIndex += Math.floor(Math.random() * 11);
						$('#playingGround').html(['<img style="top:'+top+'px;left:'+left+'px;position:absolute;z-index:'+maxIndex+'" id="'+path+num+'" src="image/ressources/'+path+'">' + $('#playingGround').html()].join());
						addToList(path, path+num);
						setSelectedItem(path+num);
					});
				}
			});

			$('.draggableItem').sortable();

			$('.inPlayingGround').resizable({
				aspectRatio : true,
				helper : 'help',
				animate : true
			});

			$('#list2 .container').on('click', function(){
				setSelectedItem($(this).find('div').attr('id').replace('list_', ''));
			});

			$('#playingGround img').on('click', function(event){
				setSelectedItem($(this).attr('id'));
				mouseX = event.clientX;
				mouseY = event.clientY;
			});

			$('#list2 .delete').on('click', function(){
				deleteImage($(this));
			});
	}, 2000);

		//NEED TO TEST!!!
		$('#playingGround').on('mousewheel DOMMouseScroll', function(event){
				if(selectedItem != null && selectedItem != ""){
					var image = document.getElementById(selectedItem);
					//Check if draggable
					if(image.draggable == false){return;}
					if(typeof event.originalEvent.detail == 'number' && event.originalEvent.detail !== 0) {
						if(event.originalEvent.detail > 0) {
							//reduce size of the image
							var posX = ($('#'+ selectedItem).width()/zoomRatio - $('#'+ selectedItem).width())/2;
							var posY = ($('#'+ selectedItem).height()/zoomRatio - $('#'+ selectedItem).height())/2;
							$('#'+ selectedItem).css({width: ($('#'+ selectedItem).width()/zoomRatio), height: ($('#'+ selectedItem).height()/zoomRatio), top: $('#'+ selectedItem).offset.top + posY, left: $('#'+ selectedItem).offset.left + posX});
							console.log('PosX : ' + posX + ' PosY : ' + posY);
							console.log(image);
							console.log('Down');
						} else if(event.originalEvent.detail < 0){
							//increase size of the image
							var posX = ($('#'+ selectedItem).width()*zoomRatio - $('#'+ selectedItem).width())/2;
							var posY = ($('#'+ selectedItem).height()*zoomRatio - $('#'+ selectedItem).height())/2;
							$('#'+ selectedItem).css({width: ($('#'+ selectedItem).width()*zoomRatio), height: ($('#'+ selectedItem).height()*zoomRatio), top: $('#'+ selectedItem).offset.top + posY, left: $('#'+ selectedItem).offset.left + posX});
							selectedItem.width() = selectedItem.width() - 2;
							console.log('PosX : ' + posX + ' PosY : ' + posY);
							console.log(image);
							console.log('Up');
						}
					  } else if (typeof event.originalEvent.wheelDelta == 'number') {
						if(event.originalEvent.wheelDelta < 0) {
							//reduce size of the image
							image.width = image.width - ((image.width/100)*5);
							//var posX = ($('#'+ selectedItem).width()/zoomRatio - $('#'+ selectedItem).width())/2;
							//var posY = ($('#'+ selectedItem).height()/zoomRatio - $('#'+ selectedItem).height())/2;
							//$('#'+ selectedItem).css({width: ($('#'+ selectedItem).width()/zoomRatio), height: ($('#'+ selectedItem).height()/zoomRatio), top: $('#'+ selectedItem).offset.top + posY, left: $('#'+ selectedItem).offset.left + posX});
						} else if(event.originalEvent.wheelDelta > 0) {
							//increase size of the image
							image.width = image.width + ((image.width/100)*5);
							//var posX = ($('#'+ selectedItem).width()*zoomRatio - $('#'+ selectedItem).width())/2;
							//var posY = ($('#'+ selectedItem).height()*zoomRatio - $('#'+ selectedItem).height())/2;
							//$('#'+ selectedItem).css({width: ($('#'+ selectedItem).width()*zoomRatio), height: ($('#'+ selectedItem).height()*zoomRatio), top: $('#'+ selectedItem).offset.top + posY, left: $('#'+ selectedItem).offset.left + posX});
						}
					}
					event.preventDefault();
				}
			});

		
		document.addEventListener('click', function(event){
			if(event.target.classList.contains('unlock')){
				lockImage(event.target);
			}
		});

		document.addEventListener('mousemove', function(event){
			moveImage(event);
		});

		document.addEventListener('mousedown', function(event){
			if(event.target.id == selectedItem){
				lbmPressed = true;
				//mouseX = event.clientX;
				//mouseY = event.clientY;
			}
		});

		document.addEventListener('mouseup', function(event){
			lbmPressed = false;
		});

		$(document).on("mousedown", '#playingGround', function(event){
			if(event.which == 2){
				event.preventDefault();
				$('#playingGround').html(['<img style="top:'+(event.clientY - 50)+'px;left:'+((event.clientX - leftMenuWidth) - 50) +'px;position:absolute;z-index:999999" id="ping" src="image/ping.png">' + $('#playingGround').html()].join());
				var myTimer = setInterval(function(){
					$( "#ping" ).remove();
					clearTimeout(myTimer);
				}, 3000);
			}
		});

	function moveImage(event){
		//Detect if left click is pressed
		var mouseDiffPosX = 0;
		var mouseDiffPosY = 0;
		var obj = document.getElementById(selectedItem);
		if(lbmPressed && selectedItem){
			//Check if draggable
			if(obj.draggable == false){return;}
			//get actual mouse position
			event.preventDefault();
			//Define the diff between first mouse position and current position
			mouseDiffPosX = event.clientX - mouseX;
			mouseDiffPosY = event.clientY - mouseY;
			var left = obj.offsetLeft + mouseDiffPosX;
			var top = obj.offsetTop + mouseDiffPosY;
			//Actualize mouse position NEED TO TEST!!
			obj.style.top = (event.clientY - (obj.height/100)*50) +"px";
			obj.style.left = ((event.clientX - leftMenuWidth) - (obj.width/100)*50) +"px";
			obj.style.visibility = "visible";
		}
	}

	//Set the selected item on the right list and put the image associate first
	function setSelectedItem(objId){
		var itemContainer = document.getElementById('list_'+objId);
		if(objId && typeof objId != 'undefined' && itemContainer){
			if(itemContainer.parentNode != null){
				//Check if draggable
				if(objId.draggable == false){return;}
				var _selectedItemContainer = document.getElementById('list_'+objId).parentElement;
				var allListObj = document.getElementsByClassName('container');
				for (var i = 0; i < allListObj.length; i++) {
					allListObj[i].classList.remove('selectedItem')
				}
				selectedItem = objId;
				_selectedItemContainer.classList.add('selectedItem');
				var allListImg = document.getElementById('playingGround').children;
				for (var i = 0; i < allListImg.length; i++) {
					allListImg[i].classList.remove('selectedImage');
				}
				var obj = document.getElementById(objId);
				if(obj){
					obj.classList.add('selectedImage');
				}
			}
		}else{
			selectedItem = "";
		}
		
		if(obj){
			//Set sizeInfo of selectedItem (bottom-right)
			$('#sizeInfo').text('Largeur : ' + obj.width + ' Hauteur : ' + obj.height);
		}
	}


	function lockImage(obj){
		$(obj).attr('draggable', true);
		var image = document.getElementById(obj.id.replace('lock_', ''));
		if (image.draggable == true){
			image.draggable = false;
		}
		else if (image.draggable == false){
			image.draggable = true;
		}

		//image.draggable = !image.draggable;
		console.log(image.draggable);
	}

	//Delete the image and the list record associate
	//Need to resolve error with after deleting element like not able to read src but not important
	function deleteImage(obj){
		if(selectedItem == obj.attr('id').replace('del_', '')){
			setSelectedItem();
		}
		var image = document.getElementById(obj.attr('id').replace('del_', ''));
		if(image){
			image.remove();
		}
		var listEntry = document.getElementById(obj.attr('id'));
		if(listEntry){
			listEntry.parentNode.parentNode.remove();
		}
	}
</script>
