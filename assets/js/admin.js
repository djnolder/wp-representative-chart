;(function ($) {
  // save the send_to_editor handler function
  window.send_to_editor_default = window.send_to_editor

  var frame

  $(document).on("click", ".set_profile_picture", function (e) {
    e.preventDefault()

    var imgElement = $(this).parents(".image_box").find(".profile_image")
    var imgIdInput = $(this).parents(".image_box").find("#profile_picture")
    var setLink = $(this).parents(".image_box").find(".set_profile_picture")
    var removeLink = $(this)
      .parents(".image_box")
      .find(".remove_profile_picture")

    // If the media frame already exists, reopen it.
    if (frame) {
      frame.open()
      return
    }

    var postid = $(this).data("postid")

    frame = wp.media({
      title: "Select or Upload Media Of Your Chosen Persuasion",
      button: {
        text: "Use this media",
      },
      multiple: false, // Set to true to allow multiple files to be selected
    })

    // When an image is selected in the media frame...
    frame.on("select", function () {
      // Get media attachment details from the frame state
      var attachment = frame.state().get("selection").first().toJSON()

      // update the DOM
      imgElement.append('<img src="' + attachment.url + '" alt="" />')
      imgIdInput.val(attachment.id)
      setLink.addClass("hidden")
      removeLink.removeClass("hidden")
    })

    // Finally, open the modal on click
    frame.open()
    return false
  })

  $(document).on("click", ".remove_profile_picture", function (e) {
    e.preventDefault()

    var imgElement = $(this).parents(".image_box").find(".profile_image")
    var imgIdInput = $(this).parents(".image_box").find(".profile_picture")
    var setLink = $(this).parents(".image_box").find(".set_profile_picture")
    var removeLink = $(this)
      .parents(".image_box")
      .find(".remove_profile_picture")

    // Updat the dDOM
    imgElement.html("")
    setLink.removeClass("hidden")
    removeLink.addClass("hidden")
    imgIdInput.val("")
  })
})(jQuery)
