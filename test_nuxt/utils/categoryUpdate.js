export default  function ({section}) {
  console.log(section)
  if (section.test.length > 0 ){
    navigateTo(`/area/${section.id}`)
  } else if (section.children.length > 0) {
    navigateTo(`/iter/1/group/${section.id}`)
  } else {
    navigateTo(`/iter/1/group/${section.id}`)
  }
}