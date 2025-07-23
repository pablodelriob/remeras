$(document).ready(function() {
  let currentSide = "front";
  let savedFrontJSON = "", savedBackJSON = "";

  $("#tshirttype").change(function() {
    $("#tshirtFacing").attr("src", $(this).val());
  });

  $('#flipback').click(function() {
    if ($(this).attr("data-original-title") === "Show Back View") {
      $(this).attr("data-original-title", "Show Front View");
      currentSide = "back";
      savedFrontJSON = JSON.stringify(canvas);
      $("#tshirtFacing").attr("src", "img/crew_back.png");
      canvas.clear();
      try { canvas.loadFromJSON(savedBackJSON); } catch(e){}
    } else {
      $(this).attr("data-original-title", "Show Back View");
      currentSide = "front";
      savedBackJSON = JSON.stringify(canvas);
      $("#tshirtFacing").attr("src", "img/crew_front.png");
      canvas.clear();
      try { canvas.loadFromJSON(savedFrontJSON); } catch(e){}
    }
    canvas.renderAll();
    setTimeout(() => canvas.calcOffset(), 200);
  });

  document.getElementById('fileToUpload').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (event) {
      fabric.Image.fromURL(event.target.result, function (img) {
        img.scaleToWidth(150);
        img.set({ left: 25, top: 50, selectable: true });
        canvas.add(img);
        canvas.setActiveObject(img);
        canvas.renderAll();
      });
    };
    reader.readAsDataURL(e.target.files[0]);
  });

  $('#saveBtn').click(function() {
    const frontJSON = currentSide === "front" ? JSON.stringify(canvas) : savedFrontJSON;
    const backJSON  = currentSide === "back" ? JSON.stringify(canvas) : savedBackJSON;

    const data = {
      name: $("#designName").val() || "Diseño sin nombre",
      type: $("#tshirttype").val(),
      color: "#ffffff", // ajustar si estás usando variable real
      sizes: { S: 1 },  // ajustar con tu lógica de talles
      design_front: frontJSON,
      design_back: backJSON
    };

    $.post('saveDesign.php', data, function(response) {
      alert(response);
    });
  });
});
