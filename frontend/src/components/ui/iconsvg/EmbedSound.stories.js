import React from 'react'
import EmbedSound from './EmbedSound'

const Story = {
  title: 'Components/ui/iconsvg/EmbedSound',
  component: EmbedSound,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <EmbedSound {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
