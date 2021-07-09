import React from 'react'
import Gallery from './Gallery'

const Story = {
  title: 'Components/ui/iconsvg/Gallery',
  component: Gallery,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Gallery {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
