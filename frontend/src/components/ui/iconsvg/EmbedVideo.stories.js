import React from 'react'
import EmbedVideo from './EmbedVideo'

const Story = {
  title: 'Components/ui/iconsvg/EmbedVideo',
  component: EmbedVideo,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <EmbedVideo {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
