import React from 'react'
import { IconSVGProps } from './model.td'

const Gallery: React.FC<IconSVGProps> = ({ size = 24, color = 'currentColor', classes = '' }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className={classes}>
    <rect x="1" y="4" width="6" height="4" rx="0.5" fill={color} />
    <rect x="1" y="10" width="6" height="4" rx="0.5" fill={color} />
    <rect x="1" y="16" width="6" height="4" rx="0.5" fill={color} />
    <rect x="9" y="4" width="6" height="4" rx="0.5" fill={color} />
    <rect x="9" y="10" width="6" height="4" rx="0.5" fill={color} />
    <rect x="9" y="16" width="6" height="4" rx="0.5" fill={color} />
    <rect x="17" y="4" width="6" height="4" rx="0.5" fill={color} />
    <rect x="17" y="10" width="6" height="4" rx="0.5" fill={color} />
    <rect x="17" y="16" width="6" height="4" rx="0.5" fill={color} />
  </svg>
)

export default Gallery
