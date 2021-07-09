import React from "react"
import { IconSVGProps } from "./model.td"

const List: React.FC<IconSVGProps> = ({ size = 24, color = "currentColor", classes = "" }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    className={classes}
    width={size}
    height={size}
    viewBox="0 0 24 24"
    fill="none"
  >
    <rect x="8" y="5" width="12" height="2" rx="1" fill={color} />
    <rect x="4" y="5" width="2" height="2" rx="1" fill={color} />
    <rect x="8" y="9" width="12" height="2" rx="1" fill={color} />
    <rect x="4" y="9" width="2" height="2" rx="1" fill={color} />
    <rect x="8" y="13" width="12" height="2" rx="1" fill={color} />
    <rect x="4" y="13" width="2" height="2" rx="1" fill={color} />
    <rect x="8" y="17" width="12" height="2" rx="1" fill={color} />
    <rect x="4" y="17" width="2" height="2" rx="1" fill={color} />
  </svg>)

export default List;
