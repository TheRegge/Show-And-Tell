import React from 'react'
import { IconSVGProps } from './model.td'

// Made by Regis Zaleman with Figma
const Paragraph: React.FC<IconSVGProps> = ({ size = 24, color = "currentColor", classes = "" }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    className={classes}
    width={size}
    height={size}
    viewBox="0 0 24 24"
    fill="none">
    <path fillRule="evenodd" clipRule="evenodd" d="M8.5 4H18C18.5523 4 19 4.44772 19 5C19 5.55228 18.5523 6 18 6H17V19C17 19.5523 16.5523 20 16 20C15.4477 20 15 19.5523 15 19V6H12V19C12 19.5523 11.5523 20 11 20C10.4477 20 10 19.5523 10 19V13H8.5C6.01472 13 4 10.9853 4 8.5C4 6.01472 6.01472 4 8.5 4ZM10 11V6H8.5C7.11929 6 6 7.11929 6 8.5C6 9.88071 7.11929 11 8.5 11H10Z"
      fill={color} />
  </svg>)

export default Paragraph
