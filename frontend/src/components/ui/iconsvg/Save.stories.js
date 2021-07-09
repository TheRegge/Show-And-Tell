import React from 'react'
import Save from '../../../components/ui/iconsvg/Save'

const Story = {
  title: 'Components/ui/iconsvg/Save',
  component: Save,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Save {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
