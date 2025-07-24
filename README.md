-- ZeeHub v7 - Full Script (Fly with GUI Speed Slider, Mobile Support)

local AcrylicLibrary = loadstring(game:HttpGetAsync("https://raw.githubusercontent.com/Zee4625/Blox-Fruits/refs/heads/main/Zee", true))()
local TextEffect = AcrylicLibrary.TextEffect

AcrylicLibrary.Theme:HightGreen()

local Window = AcrylicLibrary:CreateWindow({
    Title = TextEffect:AddColor("ZeeHUB - v7", Color3.fromRGB(0, 255, 0)),
    Logo = "rbxassetid://82858721461099",
    Keybind = Enum.KeyCode.LeftControl,
})

local player = game.Players.LocalPlayer
local character = player.Character or player.CharacterAdded:Wait()
local humanoidRootPart = character:WaitForChild("HumanoidRootPart")
local humanoid = character:WaitForChild("Humanoid")

local moveSpeed = 39
local flightActive = false
local flyActive = false
local flyConnection
local bodyGyro, bodyVelocity
local xrayEnabled = false
local spyTarget = nil

local UserInputService = game:GetService("UserInputService")
local RunService = game:GetService("RunService")

local isMobile = UserInputService.TouchEnabled

local function rebindCharacter()
    character = player.Character or player.CharacterAdded:Wait()
    humanoidRootPart = character:WaitForChild("HumanoidRootPart")
    humanoid = character:WaitForChild("Humanoid")
end

player.CharacterAdded:Connect(rebindCharacter)

local function moveWithKeys()
    if not humanoidRootPart then return end
    local direction = Vector3.new(0, 0, 0)

    if UserInputService:IsKeyDown(Enum.KeyCode.W) then
        direction += humanoidRootPart.CFrame.LookVector
    end
    if UserInputService:IsKeyDown(Enum.KeyCode.S) then
        direction -= humanoidRootPart.CFrame.LookVector
    end
    if UserInputService:IsKeyDown(Enum.KeyCode.A) then
        direction -= humanoidRootPart.CFrame.RightVector
    end
    if UserInputService:IsKeyDown(Enum.KeyCode.D) then
        direction += humanoidRootPart.CFrame.RightVector
    end

    humanoidRootPart.Velocity = direction * moveSpeed
end

-- Fly Speed GUI setup
local flySpeedGui = Instance.new("ScreenGui")
flySpeedGui.Name = "FlySpeedGui"
flySpeedGui.ResetOnSpawn = false
flySpeedGui.IgnoreGuiInset = true
flySpeedGui.Parent = player:WaitForChild("PlayerGui")

local frame = Instance.new("Frame")
frame.Size = UDim2.new(0, 220, 0, 70)
frame.Position = UDim2.new(1, -230, 0, 30)
frame.BackgroundColor3 = Color3.fromRGB(30, 30, 30)
frame.BackgroundTransparency = 0.2
frame.BorderSizePixel = 0
frame.Visible = false
frame.Parent = flySpeedGui

local label = Instance.new("TextLabel")
label.Size = UDim2.new(1, 0, 0, 25)
label.Position = UDim2.new(0, 0, 0, 5)
label.BackgroundTransparency = 1
label.Text = "Fly Speed"
label.Font = Enum.Font.GothamBold
label.TextColor3 = Color3.fromRGB(0, 255, 120)
label.TextSize = 20
label.Parent = frame

local sliderFrame = Instance.new("Frame")
sliderFrame.Size = UDim2.new(1, -30, 0, 30)
sliderFrame.Position = UDim2.new(0, 15, 0, 35)
sliderFrame.BackgroundColor3 = Color3.fromRGB(60, 60, 60)
sliderFrame.BorderSizePixel = 0
sliderFrame.Parent = frame

local sliderBar = Instance.new("Frame")
sliderBar.Size = UDim2.new(0.2, 0, 1, 0)
sliderBar.BackgroundColor3 = Color3.fromRGB(0, 255, 120)
sliderBar.BorderSizePixel = 0
sliderBar.Parent = sliderFrame

local sliderButton = Instance.new("ImageButton")
sliderButton.Size = UDim2.new(0, 22, 1.2, 0)
sliderButton.Position = UDim2.new(0.2, -11, -0.1, 0)
sliderButton.BackgroundColor3 = Color3.fromRGB(0, 255, 120)
sliderButton.AutoButtonColor = false
sliderButton.Image = "rbxassetid://3570695787"
sliderButton.Parent = sliderFrame

local dragging = false

local function updateSpeed(inputPosX)
    local rel = math.clamp(inputPosX - sliderFrame.AbsolutePosition.X, 0, sliderFrame.AbsoluteSize.X)
    local percent = rel / sliderFrame.AbsoluteSize.X
    sliderBar.Size = UDim2.new(percent, 0, 1, 0)
    sliderButton.Position = UDim2.new(percent, -11, -0.1, 0)
    moveSpeed = math.floor(percent * 200)
    if moveSpeed < 1 then moveSpeed = 1 end
end

sliderButton.InputBegan:Connect(function(input)
    if input.UserInputType == Enum.UserInputType.MouseButton1 or input.UserInputType == Enum.UserInputType.Touch then
        dragging = true
    end
end)

sliderButton.InputEnded:Connect(function(input)
    if input.UserInputType == Enum.UserInputType.MouseButton1 or input.UserInputType == Enum.UserInputType.Touch then
        dragging = false
    end
end)

UserInputService.InputChanged:Connect(function(input)
    if dragging and (input.UserInputType == Enum.UserInputType.MouseMovement or input.UserInputType == Enum.UserInputType.Touch) then
        updateSpeed(input.Position.X)
    end
end)

-- Fly function
local function toggleFly(state)
    flyActive = state
    if flyConnection then flyConnection:Disconnect() end
    if not flyActive then
        if bodyGyro then bodyGyro:Destroy() end
        if bodyVelocity then bodyVelocity:Destroy() end
        frame.Visible = false
        return
    end

    frame.Visible = true

    bodyGyro = Instance.new("BodyGyro")
    bodyGyro.P = 9e4
    bodyGyro.maxTorque = Vector3.new(9e9, 9e9, 9e9)
    bodyGyro.cframe = humanoidRootPart.CFrame
    bodyGyro.Parent = humanoidRootPart

    bodyVelocity = Instance.new("BodyVelocity")
    bodyVelocity.velocity = Vector3.new(0, 0, 0)
    bodyVelocity.maxForce = Vector3.new(9e9, 9e9, 9e9)
    bodyVelocity.Parent = humanoidRootPart

    flyConnection = RunService.Heartbeat:Connect(function()
        if not flyActive then return end
        bodyGyro.cframe = workspace.CurrentCamera.CFrame

        local moveVec = Vector3.new()
        if UserInputService:IsKeyDown(Enum.KeyCode.W) or (isMobile and UserInputService:IsKeyDown(Enum.KeyCode.Up)) then
            moveVec += workspace.CurrentCamera.CFrame.LookVector
        end
        if UserInputService:IsKeyDown(Enum.KeyCode.S) or (isMobile and UserInputService:IsKeyDown(Enum.KeyCode.Down)) then
            moveVec -= workspace.CurrentCamera.CFrame.LookVector
        end
        if UserInputService:IsKeyDown(Enum.KeyCode.A) or (isMobile and UserInputService:IsKeyDown(Enum.KeyCode.Left)) then
            moveVec -= workspace.CurrentCamera.CFrame.RightVector
        end
        if UserInputService:IsKeyDown(Enum.KeyCode.D) or (isMobile and UserInputService:IsKeyDown(Enum.KeyCode.Right)) then
            moveVec += workspace.CurrentCamera.CFrame.RightVector
        end

        if moveVec.Magnitude > 0 then
            bodyVelocity.velocity = moveVec.Unit * moveSpeed
        else
            bodyVelocity.velocity = Vector3.new(0, 0, 0)
        end
    end)
end

-- Add toggle to UI
local TabMovement = Window:AddTab({Title = "HOME", Icon = "home"})

TabMovement:AddToggle({
    Title = "Fly",
    Tip = "Toggle Fly mode with speed control",
    Default = false,
    Callback = function(val)
        toggleFly(val)
    end,
})
