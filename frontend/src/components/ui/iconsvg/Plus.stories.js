import React from 'react'
import Plus from './Plus'

const Story = {
  title: 'Components/ui/iconsvg/Plus',
  component: Plus,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Plus {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
