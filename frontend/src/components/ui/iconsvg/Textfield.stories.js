import React from 'react'
import Textfield from '../../../components/ui/iconsvg/Textfield'

const Story = {
  title: 'Components/ui/iconsvg/Textfield',
  component: Textfield,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Textfield {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
