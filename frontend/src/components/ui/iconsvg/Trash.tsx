import React from "react"
import { IconSVGProps } from "./model.td";

const Trash: React.FC<IconSVGProps> = ({ size = 24, color = "currentColor", classes = "" }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={classes}>
    <path fillRule="evenodd" clipRule="evenodd" d="M17 4.9C17 4.95523 17.0448 5 17.1 5L20 5C20.5523 5 21 5.44772 21 6C21 6.55228 20.5523 7 20 7H19.1C19.0448 7 19 7.04477 19 7.1V21C19 22.1046 18.1046 23 17 23H7C5.89543 23 5 22.1046 5 21V7.1C5 7.04477 4.95523 7 4.9 7H4C3.44772 7 3 6.55228 3 6C3 5.44772 3.44772 5 4 5L6.9 5C6.95523 5 7 4.95523 7 4.9V3C7 1.89543 7.89543 1 9 1H15C16.1046 1 17 1.89543 17 3V4.9ZM7.1 7C7.04477 7 7 7.04477 7 7.1V20.5C7 20.7761 7.22386 21 7.5 21H16.5C16.7761 21 17 20.7761 17 20.5V7.1C17 7.04477 16.9552 7 16.9 7H7.1ZM9.1 5C9.04477 5 9 4.95523 9 4.9V3.5C9 3.22386 9.22386 3 9.5 3H14.5C14.7761 3 15 3.22386 15 3.5V4.9C15 4.95523 14.9552 5 14.9 5H9.1Z" fill={color} />
    <rect x="9" y="10" width="2" height="8" rx="1" fill={color} />
    <rect x="13" y="10" width="2" height="8" rx="1" fill={color} />
  </svg>
)

export default Trash
