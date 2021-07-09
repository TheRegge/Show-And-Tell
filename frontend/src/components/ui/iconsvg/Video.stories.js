import React from 'react'
import Video from './Video'

const Story = {
  title: 'Components/ui/iconsvg/Video',
  component: Video,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Video {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
