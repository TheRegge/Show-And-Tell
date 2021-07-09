import React from 'react'
import Sound from './Sound'

const Story = {
  title: 'Components/ui/iconsvg/Sound',
  component: Sound,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Sound {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
