import React from 'react'
import File from './File'

const Story = {
  title: 'Components/ui/iconsvg/File',
  component: File,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <File {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
