import React from 'react'
import {
  buttonPrimary,
  buttonSuccess,
  buttonInfo,
  buttonDanger,
  buttonNeutral,
  buttonWarning,
} from './theme'

const variantsMap = {
  'primary': buttonPrimary,
  'success': buttonSuccess,
  'info': buttonInfo,
  'danger': buttonDanger,
  'neutral': buttonNeutral,
  'warning': buttonWarning,
}

type Props = {
  callback: React.MouseEventHandler<HTMLButtonElement>,
  text: string,
  ariaLabel?: string,
  icon?: JSX.Element,
  iconPosition?: 'left' | 'right',
  classes?: string,
  variant?: 'primary' | 'success' | 'info' | 'danger' | 'warning' | 'neutral',
}

const Button: React.FC<Props> = ({ callback, text, ariaLabel, icon, iconPosition = 'left', classes, variant }) => {

  let css: string = `flex items-center justify-around ${iconPosition === 'left' ? 'flex-row' : 'flex-row-reverse'} focus:outline-none focus:ring-2 focus:ring-opacity-75 gap-x-2 h-auto py-2 px-4 hover:shadow-none rounded-lg shadow-md text-lg capitalize transition-colors duration-200`
  if (classes) {
    css = `${css} ${classes}`
  }

  if (variant) {
    css = `${css} ${variantsMap[variant]}`
  } else {
    css = `${css} ${variantsMap['neutral']}`
  }

  return (
    <button
      className={css}
      aria-label={ariaLabel}
      onClick={callback}
    >
      {icon}
      <div>
        {text}
      </div>
    </button>
  )
}

export default Button
