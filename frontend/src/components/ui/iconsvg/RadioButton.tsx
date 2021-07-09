import React from 'react'
import { IconSVGProps } from './model.td'

const RadioButton: React.FC<IconSVGProps> = ({ size = 24, color = 'currentColor', classes = '' }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    className={classes}
    width={size}
    height={size}
    viewBox="0 0 24 24"
    fill="none"
  >
    <circle cx="12" cy="12" r="6" stroke={color} strokeWidth="2" />
    <circle cx="12" cy="12" r="3" fill={color} />
  </svg>
)

export default RadioButton
