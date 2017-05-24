<div id="myModal" class="modal">

    <!-- The Close Button -->
    <span class="close" id="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>

</div>

<script>
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption

    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");


    // Get the <span> element that closes the modal
    var span = document.getElementById("close");



    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    modalImg.onclick = function (e) {
        e.stopPropagation();
    }

    modal.onclick = function () {
        modal.style.display="none";
    }



    function img(id , cap){
        modal.style.display = "block";
        modalImg.src = id.src;
        captionText.innerHTML = decodeURIComponent(cap);
        document.getElementById("body").style.overflow="hidden";
    }

    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("myImg");
        if (n > x.length) {slideIndex = 1}
        if (n < 1) {slideIndex = x.length}
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[slideIndex-1].style.display = "block";
    }

</script>


</script>