import React from 'react'

interface Props {
  checked: boolean,
  id?: string,
  label: string,
  onChange: React.ChangeEventHandler<HTMLInputElement>,
}
const LargeSwitch: React.FC<Props> = ({ checked, onChange, id, label }) => {

  const stateStyles: string = checked ?
    'bg-green-500 transform translate translate-x-4' :
    'bg-gray-500'

  return (
    <label htmlFor={id} className="flex items-center space-x-2" tabIndex={0}>
      <div className="bg-gray-200 w-11 rounded-2xl border-2 border-gray-300">
        <div className={`w-6 h-6 rounded-full transition-all duration-200 transform ${stateStyles}`}></div>
        <input id={id} className="sr-only" type="checkbox" {...checked && 'checked'} onChange={onChange} />
      </div>
      <span>
        {label}
      </span>
    </label>
  )
};


export default LargeSwitch
