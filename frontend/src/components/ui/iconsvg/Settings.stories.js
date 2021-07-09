import React from 'react'
import Settings from '../../../components/ui/iconsvg/Settings'

const Story = {
  title: 'Components/ui/iconsvg/Settings',
  component: Settings,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Settings {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
