import React from 'react'
import Upload from '../../../components/ui/iconsvg/Upload'

const Story = {
  title: 'Components/ui/iconsvg/Upload',
  component: Upload,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Upload {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
