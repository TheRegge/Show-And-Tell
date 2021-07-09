import React from 'react'
import Checkbox from './Checkbox'

const Story = {
  title: 'Components/ui/iconsvg/Checkbox',
  component: Checkbox,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Checkbox {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
