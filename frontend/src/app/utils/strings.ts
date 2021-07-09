/**
 * Transform a string to Title Case
 * 
 * @author Regis Zaleman
 * @param string The String to transform to title case
 * @returns string
 */
export function titleCase(string: string): string {
  let sentence = string.toLowerCase().split(" ")
  let arr = sentence.map(word => word[0].toUpperCase() + word.slice(1))
  return arr.join(' ')
}