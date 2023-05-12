
export default function crumbsTitle(iter) {
  const title = iter.alias ? iter.alias 
    :  iter.title.length > 30 ? iter.title.slice(1, 30) : iter.title
  return title
}
