import React from "react"
import { IconSVGProps } from "./model.td"


const Plus: React.FC<IconSVGProps> = ({ size = 24, color = "currentColor", classes = "" }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={classes}>
    <path d="M12 3C11.4477 3 11 3.44772 11 4V10.9C11 10.9552 10.9552 11 10.9 11H4C3.44772 11 3 11.4477 3 12C3 12.5523 3.44772 13 4 13H10.9C10.9552 13 11 13.0448 11 13.1V20C11 20.5523 11.4477 21 12 21C12.5523 21 13 20.5523 13 20V13.1C13 13.0448 13.0448 13 13.1 13H20C20.5523 13 21 12.5523 21 12C21 11.4477 20.5523 11 20 11H13.1C13.0448 11 13 10.9552 13 10.9V4C13 3.44772 12.5523 3 12 3Z" fill={color} />
  </svg>)

export default Plus
