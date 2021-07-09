import React from 'react'
import { IconSVGProps } from './model.td'

const Video: React.FC<IconSVGProps> = ({ size = 24, color = 'currentColor', classes = '' }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    className={classes}
    width={size}
    height={size}
    viewBox="0 0 24 24"
    fill="none"
  >
    <path fillRule="evenodd" clipRule="evenodd" d="M3 4C2.44772 4 2 4.44772 2 5V19C2 19.5523 2.44772 20 3 20H21C21.5523 20 22 19.5523 22 19V5C22 4.44772 21.5523 4 21 4H3ZM4.25 6C4.11193 6 4 6.11193 4 6.25V17.75C4 17.8881 4.11193 18 4.25 18H19.75C19.8881 18 20 17.8881 20 17.75V6.25C20 6.11193 19.8881 6 19.75 6H4.25Z" fill={color} />
    <path d="M10.8201 7.68341L15.5391 11.6159C15.7789 11.8158 15.7789 12.1842 15.5391 12.3841L10.8201 16.3166C10.4944 16.588 10 16.3564 10 15.9325L10 8.06752C10 7.6436 10.4944 7.41202 10.8201 7.68341Z" fill={color} />
  </svg>
)

export default Video
