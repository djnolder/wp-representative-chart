document.addEventListener("DOMContentLoaded", function () {
  const region_color = "#006064"
  const outline_color = "#547876"
  const region_highlight = "#50A0A4"
  const region_selected = "#be9954" //#FBFAF5   be9954
  const reps = document.querySelectorAll(".rep-data")
  const map_tooltip = document.getElementById("regions_map_tooltip")
  let requesting = false

  // Moving away from google geochart
  const map_wrapper = document.getElementById("regions_map")
  const map = map_wrapper.children[0]
  let states = map.getElementsByTagName("path")
  const repdata = JSON.parse(map_wrapper.dataset.locationdata)

  console.log(states)
  for (var i = 0; i < states.length; i++) {
    states[i].dataset.reps = repdata[states[i].id]

    states[i].addEventListener(
      "mouseover",
      function (e) {
        map_tooltip.getElementsByClassName("state")[0].innerHTML =
          this.dataset.name
        map_tooltip.getElementsByClassName("count")[0].innerHTML =
          this.dataset.reps
        map_tooltip.classList.remove("hidden")

        if (!this.getAttribute("selected")) {
          this.style.fill = region_highlight
        }
      },
      false
    )
    states[i].addEventListener(
      "mouseout",
      function (e) {
        if (!this.getAttribute("selected")) {
          this.style.fill = region_color
          map_tooltip.classList.add("hidden")
        }
      },
      false
    )
    states[i].addEventListener(
      "click",
      function (e) {
        // first remove the state that is currently selected
        let selected = map.querySelectorAll("[selected]")
        for (var s = 0; s < selected.length; s++) {
          selected[s].removeAttribute("selected")
          selected[s].style.fill = region_color
        }
        // tag the new selected state
        this.style.fill = region_selected
        this.setAttribute("selected", true)

        // update the reps area
        let region = this.dataset.name
        var count = 0

        reps.forEach(function (element, index, array) {
          let r = JSON.parse(element.dataset.regions)
          if (r.includes(region)) {
            element.classList.add("visible")
            count++
          } else {
            element.classList.remove("visible")
          }
        })
        if (count == 1) {
          document.getElementById("rep-regions-heading").innerHTML =
            "There is " + count + " rep in <b>" + region + "</b>."
        } else {
          document.getElementById("rep-regions-heading").innerHTML =
            "There are " + count + " reps in <b>" + region + "</b>."
        }
      },
      false
    )

    const onPointerMove = async (e) => {
      const tooltip_width = map_tooltip.offsetWidth
      if (requesting) return
      requesting = true
      await new Promise((r) => requestAnimationFrame(r))

      const tooltip_height = map_tooltip.offsetHeight

      map_tooltip.style.left = `${e.pageX - tooltip_width - 10}px`
      map_tooltip.style.top = `${e.pageY - tooltip_height - 10}px`
      requesting = false
    }

    map.addEventListener("pointermove", onPointerMove)
  }
})
