import React from 'react'
import { IconSVGProps } from './model.td'

const File: React.FC<IconSVGProps> = ({ size = 24, color = 'currentColor', classes = '' }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={classes}>
    <rect x="8" y="10" width="9" height="1.5" rx="0.75" fill={color} />
    <rect x="8" y="13" width="9" height="1.5" rx="0.75" fill={color} />
    <rect x="8" y="16" width="9" height="1.5" rx="0.75" fill={color} />
    <path fillRule="evenodd" clipRule="evenodd" d="M6 2C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H19C20.1046 22 21 21.1046 21 20V7.41421C21 6.88378 20.7893 6.37507 20.4142 6L17 2.58579C16.6249 2.21071 16.1162 2 15.5858 2H6ZM15 4.2C15 4.08954 14.9105 4 14.8 4L6.2 4C6.08954 4 6 4.08954 6 4.2V19.8C6 19.9105 6.08954 20 6.2 20H18.8C18.9105 20 19 19.9105 19 19.8V8.06341C19 8.02751 18.9684 8 18.9325 8V8H15.5C15.2239 8 15 7.77614 15 7.5V4.2Z" fill={color} />
  </svg>
)

export default File
